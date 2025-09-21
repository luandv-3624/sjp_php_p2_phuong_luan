<?php

namespace App\Repositories\Venue;

use App\Models\Venue;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\VenuesSortBy;
use App\Enums\SortOrder;
use App\Enums\VenueStatus;

class VenueRepository implements VenueRepositoryInterface
{
    protected const PAGE_SIZE = 10;

    public function create(array $data)
    {
        try {
            $venue = Venue::create($data);
            $venue->load('ward.province');

            return $venue;
        } catch (\Exception $e) {
            Log::error('Venue creation failed: '.$e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findAll(array $filters, ?int $pageSize): LengthAwarePaginator
    {
        try {
            $sortBy = VenuesSortBy::tryFrom($filters['sortBy'] ?? null) ?? VenuesSortBy::CREATED_AT;
            $sortOrder = SortOrder::tryFrom($filters['sortOrder'] ?? null) ?? SortOrder::DESC;
            $perPage = $pageSize ?? self::PAGE_SIZE;

            $ownerId  = $filters['ownerId'] ?? null;
            $wardId   = $filters['wardId'] ?? null;
            $status   = VenueStatus::tryFrom($filters['status'] ?? null);
            $name     = $filters['name'] ?? null;
            $address  = $filters['address'] ?? null;

            $venues = Venue::with(['owner', 'ward', 'managers'])
                ->when(isset($ownerId), function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                })
                ->when(isset($wardId), function ($query) use ($wardId) {
                    $query->where('ward_id', $wardId);
                })
                ->when(isset($status), function ($query) use ($status) {
                    $query->where('status', $status->value);
                })
                ->when($name, function ($query, $name) {
                    $query->where('name', 'like', '%' . $name . '%');
                })
                ->when($address, function ($query, $address) {
                    $query->where('address', 'like', '%' . $address . '%');
                })
                ->orderBy($sortBy->value, $sortOrder->value)
                ->paginate($perPage);

            return $venues;
        } catch (\Exception $e) {
            Log::error('Fetch all venues failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $venueId, array $data)
    {
        try {
            $venue = Venue::find($venueId);
            $venue->update($data);
            $venue->load('ward.province');

            return $venue;
        } catch (\Exception $e) {
            Log::error('Venue update failed: ' . $e->getMessage(), [
                'venue_id' => $venueId,
                'data'     => $data,
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function delete(int $venueId): bool
    {
        try {
            $venue = Venue::find($venueId);

            return $venue->delete();
        } catch (\Exception $e) {
            Log::error('Venue deletion failed: ' . $e->getMessage(), [
                'venue_id' => $venueId,
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function findById(int $venueId)
    {
        try {
            return Venue::with([
                'owner',
                'ward.province',
                'spaces.type',
                'spaces.priceType',
                'spaces.amenities',
                'managers'
            ])->find($venueId);
        } catch (\Exception $e) {
            Log::error('Get venue detail failed: ' . $e->getMessage(), [
                'venue_id' => $venueId,
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function findByIdCustomer(int $venueId)
    {
        try {
            return Venue::with([
                'ward.province',
                'spaces.type',
                'spaces.priceType',
                'spaces.amenities',
            ])->find($venueId);
        } catch (\Exception $e) {
            Log::error('Get venue detail failed: ' . $e->getMessage(), [
                'venue_id' => $venueId,
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function addManager(int $venueId, array $userIds)
    {
        $venue = Venue::find($venueId);

        $userIds = array_map('intval', $userIds);

        $venue->managers()->syncWithoutDetaching($userIds);

        return $venue->load('managers');
    }
}
