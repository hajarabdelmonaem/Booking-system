<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UserBookingService
{
    
    private ProviderServicesService $providerServicesService;

    public function __construct(ProviderServicesService $providerServicesService)
    {
        $this->providerServicesService = $providerServicesService;
    }

    public function listBookings(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::where('user_id', $user->id)
            ->with(['provider', 'service'])
            ->latest('date')
            ->paginate($perPage);
    }

    public function cancelBooking(User $user, Booking $booking): Booking
    {
        if ((int) $booking->user_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'booking' => [__('You cannot cancel this booking.')],
            ]);
        }

        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages([
                'booking' => [__('This booking is already cancelled.')],
            ]);
        }

        $booking->update(['status' => 'cancelled']);

        return $booking;
    }

    public function createBooking(User $user, array $data): Booking
    {
        $service = Service::findOrFail($data['service_id']);
    
        $date = Carbon::parse($data['date']);
    
        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $data['start_at']);
    
        $end = $start->copy()->addMinutes($service->duration);
    
        $slots = collect($this->providerServicesService->getAvailableSlots(
            $service->id,
            new Request(['date' => $date->format('Y-m-d')])
        ));
    
        $exists = $slots->contains(function ($slot) use ($start, $end) {
    
            return $slot['start'] === $start->format('H:i')
                && $slot['end'] === $end->format('H:i');
        });
    
        if (! $exists) {
            throw ValidationException::withMessages([
                'start_at' => ['Time slot is not available'],
            ]);
        }
    
        return Booking::create([
            'user_id'     => $user->id,
            'provider_id' => $service->provider_id,
            'service_id'  => $service->id,
            'date'        => $date->format('Y-m-d'),
            'price'       => $service->price,
            'start_at'    => $start->format('H:i'),
            'end_at'      => $end->format('H:i'),
            'status'      => 'pending',
        ]);
    }

}
