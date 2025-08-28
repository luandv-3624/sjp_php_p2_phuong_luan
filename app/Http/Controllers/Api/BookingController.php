<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\IndexByUserRequest;
use App\Http\Requests\Booking\IndexRequest;
use App\Http\Requests\Booking\StoreRequest;
use App\Models\Booking;
use App\Services\Booking\BookingServiceInterface;
use App\Http\Requests\Booking\UpdateBookingStatusRequest;

class BookingController extends Controller
{
    public function __construct(private BookingServiceInterface $bookingService)
    {
    }

    public function store(StoreRequest $request)
    {
        $user = $request->user();

        return $this->bookingService->create([
            ...$request->validated(),
            'user_id' => $user->id,
        ]);
    }

    public function show(Booking $booking)
    {
        return $this->bookingService->findById($booking->id);
    }

    public function index(IndexRequest $request)
    {
        $query = $request->validated();

        return $this->bookingService->findAll($query, $query['perPage'] ?? null);
    }

    public function indexByUser(IndexByUserRequest $request)
    {
        $user = $request->user();
        $query = $request->validated();

        return $this->bookingService->findAll(
            [...$query, 'userId' => $user->id],
            $query['pageSize'] ?? null
        );
    }

    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking)
    {
        $data = $request->validated();

        return $this->bookingService->updateStatus($booking, $data['status']);
    }
}
