<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Services\UserBookingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserBookingController extends Controller
{
    private UserBookingService $userBookingService;

    public function __construct(UserBookingService $userBookingService)
    {
        $this->userBookingService = $userBookingService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $perPage = 10;
        $bookings = $this->userBookingService->listBookings($user, $perPage);

        return BookingResource::collection($bookings)
            ->response()
            ->setStatusCode(200);
    }

    public function store(StoreUserBookingRequest $request): JsonResponse
    {
        $user = auth()->user();
        $booking = $this->userBookingService->createBooking($user, $request->validated());

        return BookingResource::make($booking)
            ->response()
            ->setStatusCode(200);
    }

    public function cancel(Booking $booking): JsonResponse
    {
        $updated = $this->userBookingService->cancelBooking(auth()->user(), $booking);
        return response()->json(['message' => 'Booking cancelled successfully'], 200);
    }
}
