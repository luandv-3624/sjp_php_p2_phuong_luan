<?php

namespace App\Repositories\Booking;

use App\Enums\BookingPaymentStatus;
use App\Enums\BookingsSortBy;
use App\Enums\BookingStatus;
use App\Enums\PriceType;
use App\Enums\SortOrder;
use App\Enums\SpaceStatus;
use App\Models\Booking;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingRepository implements BookingRepositoryInterface
{
    public const PAGE_SIZE = 12;

    public function create(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $space = Space::with('priceType')->find($data['space_id']);
            if (!$space) {
                throw new NotFoundHttpException(__('booking.space_not_found'));
            }

            if ($space->status === SpaceStatus::UNAVAILABLE->value) {
                throw new ConflictHttpException(__('booking.space_unavailable'));
            }

            [$startTime, $endTime, $totalPrice] = $this->normalizeTimeAndPrice(
                Carbon::parse($data['start_time']),
                Carbon::parse($data['end_time']),
                PriceType::from($space->priceType->code),
                $space->price
            );

            if ($startTime->equalTo($endTime)) {
                throw new ConflictHttpException(__('booking.invalid_time'));
            }

            $isOverlap = $this->checkOverlap($space->id, $startTime, $endTime);
            if ($isOverlap) {
                throw new ConflictHttpException(__('booking.space_already_booked'));
            }

            $booking = Booking::create([
                'user_id' => $data['user_id'],
                'space_id' => $space->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_price' => $totalPrice,
            ]);

            $booking->load('user', 'space');

            return $booking;
        });
    }

    private function normalizeTimeAndPrice(Carbon $startTime, Carbon $endTime, PriceType $priceType, float $price): array
    {
        switch ($priceType) {
            case PriceType::HOUR:
                $startTime = $startTime->startOfHour();
                $endTime = $endTime->startOfHour();

                $duration = $endTime->diffInHours($startTime);
                break;
            case PriceType::DAY:
                $startTime = $startTime->startOfDay();
                $endTime = $endTime->startOfDay();

                $duration = $endTime->diffInDays($startTime);
                break;
            case PriceType::MONTH:
                $startTime = $startTime->startOfMonth();
                $endTime = $endTime->startOfMonth();

                $duration = $endTime->diffInMonths($startTime);
                break;
        }

        $totalPrice = round($duration * $price, 2);

        return [$startTime, $endTime, $totalPrice];
    }

    private function checkOverlap(int $spaceId, Carbon $startTime, Carbon $endTime): bool
    {
        return Booking::where('space_id', $spaceId)
            ->lockForUpdate()
            ->whereNotIn('status', [
                BookingStatus::PENDING->value,
                BookingStatus::REJECTED->value,
                BookingStatus::CANCELLED->value
            ])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();
    }

    public function findById(int $id): Booking
    {
        $booking = Booking::with('user', 'space')->find($id);

        if (!$booking) {
            throw new NotFoundHttpException(__('booking.booking_not_found'));
        }

        return $booking;
    }

    public function findAll(array $filter, ?int $pageSize): LengthAwarePaginator
    {
        try {
            $sortBy = BookingsSortBy::tryFrom($filter['sortBy'] ?? null) ?? BookingsSortBy::CREATED_AT;
            $sortOrder = SortOrder::tryFrom($filter['sortOrder'] ?? null) ?? SortOrder::DESC;
            $perPage = $pageSize ?? self::PAGE_SIZE;

            $userId = $filter['userId'] ?? null;
            $spaceId = $filter['spaceId'] ?? null;
            $status = BookingStatus::tryFrom($filter['status'] ?? null);
            $statusPayment = BookingPaymentStatus::tryFrom($filter['statusPayment'] ?? null);

            $startTime = isset($filter['startTime'])
                ? Carbon::parse($filter['startTime'])
                : null;
            $endTime = isset($filter['endTime'])
                ? Carbon::parse($filter['endTime'])
                : null;

            $bookings = Booking::with('user', 'space')
                ->when(isset($userId), function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->when(isset($spaceId), function ($query) use ($spaceId) {
                    $query->where('space_id', $spaceId);
                })
                ->when(isset($status), function ($query) use ($status) {
                    $query->where('status', $status->value);
                })
                ->when(isset($statusPayment), function ($query) use ($statusPayment) {
                    $query->where('status_payment', $statusPayment->value);
                })
                ->when(isset($startTime), function ($query) use ($startTime) {
                    $query->where('start_time', '>=', $startTime);
                })
                ->when(isset($endTime), function ($query) use ($endTime) {
                    $query->where('end_time', '<=', $endTime);
                })
                ->orderBy($sortBy->value, $sortOrder->value)
                ->paginate($perPage);

            return $bookings;
        } catch (\Exception $e) {
            Log::error('Fetch all bookings failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findBookingForUpdate(int $id): ?Booking
    {
        try {
            return Booking::where('id', $id)->lockForUpdate()->first();
        } catch (\Exception $e) {
            Log::error('find booking for update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateBookingPaymentStatus(Booking $booking, array $bookingUpdateData): Booking
    {
        try {
            $booking->update($bookingUpdateData);
            return $booking;
        } catch (\Exception $e) {
            Log::error('updateBookingPaymentStatus failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id ?? null,
                'data'       => $bookingUpdateData,
                'trace'      => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
