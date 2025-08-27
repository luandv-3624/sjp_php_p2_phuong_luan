<?php

namespace App\Services\Booking;

use App\Helpers\ApiResponse;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Repositories\Booking\BookingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingService implements BookingServiceInterface
{
    public function __construct(private BookingRepositoryInterface $bookingRepo)
    {
    }

    public function create(array $data): JsonResponse
    {
        return ApiResponse::success(new BookingResource($this->bookingRepo->create($data)), __('booking.create_success'), Response::HTTP_CREATED);
    }

    public function findById(int $id): JsonResponse
    {
        return ApiResponse::success(new BookingResource($this->bookingRepo->findById($id)));
    }

    public function findAll(array $filters, ?int $pageSize): JsonResponse
    {
        return ApiResponse::success(new BookingCollection($this->bookingRepo->findAll($filters, $pageSize)));
    }
}
