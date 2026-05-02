<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Provider;
use App\Models\Service;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProviderServicesService
{


    public function providerServices($providerId): Collection
    {
        return Service::where('provider_id', $providerId)->orderBy('name')->get();
    }

    public function getService( $id): Service
    {
        return Service::whereKey($id)->firstOrFail();
    }

    public function createService(array $data): Service
    {
        $provider = auth()->user()->provider;
        return $provider->services()->create($data);
    }

    public function getAvailableSlots($serviceId, Request $request): array
    {
        $service = Service::findOrFail($serviceId);
    
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : now();
    
        $day = $date->format('Y-m-d');
        $dayNum = $date->dayOfWeek;
    
        $availabilities = $service->provider->availabilities()
            ->where('day', $dayNum)
            ->get();
    
        if ($availabilities->isEmpty()) {
            return [];
        }
    
        $bookings = Booking::where('provider_id', $service->provider_id)
            ->whereDate('date', $day)
            ->blocking()
            ->get();
    
        $duration = $service->duration;
        $slots = [];
    
        foreach ($availabilities as $availability) {
    
            $start = Carbon::parse("$day {$availability->start_at}");
            $end   = Carbon::parse("$day {$availability->end_at}");
    
            while ($start->copy()->addMinutes($duration) <= $end) {
    
                $slotStart = $start->copy();
                $slotEnd = $slotStart->copy()->addMinutes($duration);
    
                $isBusy = $bookings->first(function ($booking) use ($slotStart, $slotEnd, $day) {
                    return $slotStart < Carbon::parse("$day {$booking->end_at}")
                        && $slotEnd > Carbon::parse("$day {$booking->start_at}");
                });
    
                if (! $isBusy) {
                    $slots[] = [
                        'start' => $slotStart->format('H:i'),
                        'end'   => $slotEnd->format('H:i'),
                    ];
                }
    
                $start->addMinutes($duration);
            }
        }
    
        return $slots;
    }
}
