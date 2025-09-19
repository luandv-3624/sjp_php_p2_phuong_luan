<?php

namespace App\Repositories\Space;

use App\Enums\BookingStatus;
use App\Enums\SortOrder;
use App\Enums\SpacesSortBy;
use App\Models\PriceType;
use App\Models\Space;
use App\Models\SpaceType;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpaceRepository implements SpaceRepositoryInterface
{
    public const PAGE_SIZE = 10;

    public function create(array $data): Space
    {
        try {
            $space = Space::create($data);
            $space->load('type', 'priceType');
            return $space;
        } catch (\Exception $e) {
            Log::error('Create space failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateById(int $id, array $data): Space
    {
        try {
            $space = Space::find($id);

            if (!$space) {
                throw new NotFoundHttpException(__('space.space_not_found'));
            }

            $space->update($data);
            $space->load(['type', 'priceType']);

            return $space;
        } catch (\Exception $e) {
            Log::error('Update space failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): Space
    {
        $space = Space::with(['type', 'priceType', 'venue', 'amenities'])->find($id);

        if (!$space) {
            throw new NotFoundHttpException(__('space.space_not_found'));
        }

        return $space;
    }

    public function findAllByVenue(int $venueId): Collection
    {
        return Space::with(['type', 'priceType'])->where('venue_id', $venueId)->orderBy('created_at', 'desc')->get();
    }

    public function findAll(array $filters, ?int $pageSize): LengthAwarePaginator
    {
        $sortBy = SpacesSortBy::tryFrom($filters['sortBy'] ?? null) ?? SpacesSortBy::CREATED_AT;
        $sortOrder = SortOrder::tryFrom($filters['sortOrder'] ?? null) ?? SortOrder::DESC;
        $pageSize = $pageSize ?? self::PAGE_SIZE;

        $venueId = $filters['venueId'] ?? null;
        $wardId = $filters['wardId'] ?? null;
        $provinceId = $filters['provinceId'] ?? null;
        $name = $filters['name'] ?? null;
        $spaceTypeId = $filters['spaceTypeId'] ?? null;
        $minCapacity = $filters['minCapacity'] ?? null;
        $maxCapacity = $filters['maxCapacity'] ?? null;
        $priceTypeId = $filters['priceTypeId'] ?? null;
        $minPrice = $filters['minPrice'] ?? null;
        $maxPrice = $filters['maxPrice'] ?? null;
        $startTime = isset($filters['startTime']) ? Carbon::parse($filters['startTime']) : null;
        $endTime = isset($filters['endTime']) ? Carbon::parse($filters['endTime']) : null;

        $query = Space::query()
            ->with(['venue.ward.province', 'type', 'priceType'])
            ->where('spaces.status', 'available')
            ->whereHas('venue', function ($q) {
                $q->where('status', 'approved');
            });

        if ($venueId) {
            $query->where('venue_id', $venueId);
        }

        if ($provinceId) {
            $query->whereHas('venue.ward', function ($q) use ($provinceId) {
                $q->where('province_id', $provinceId);
            });
        } elseif ($wardId) {
            $query->whereHas('venue', function ($q) use ($wardId) {
                $q->where('ward_id', $wardId);
            });
        }

        if ($name) {
            $query->where('spaces.name', 'like', '%' . $name . '%');
        }

        if ($spaceTypeId) {
            $query->where('space_type_id', $spaceTypeId);
        }

        if ($minCapacity) {
            $query->where('capacity', '>=', $minCapacity);
        }
        if ($maxCapacity) {
            $query->where('capacity', '<=', $maxCapacity);
        }

        if ($priceTypeId) {
            $query->where('price_type_id', $priceTypeId);
        }
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($startTime && $endTime) {
            $startTime = $startTime->startOfHour();
            $endTime = $endTime->startOfHour();

            $query->whereDoesntHave('bookings', function ($q) use ($startTime, $endTime) {
                $q->whereNotIn('status', [
                    BookingStatus::PENDING->value,
                    BookingStatus::REJECTED->value,
                    BookingStatus::CANCELLED->value
                ])
                    ->where(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<', $endTime)
                            ->where('end_time', '>', $startTime);
                    });
            });
        }

        return $query
            ->orderBy($sortBy->value, $sortOrder->value)
            ->paginate($pageSize);
    }
    public function findAllPriceTypes(): Collection
    {
        return PriceType::select('id', 'name', 'name_en')
            ->orderBy('name')
            ->get();
    }
    public function findAllSpaceTypes(): Collection
    {
        return SpaceType::select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
