<?php

namespace App\Repositories\Amenity;

use App\Models\Amenity;
use Illuminate\Support\Facades\Log;

class AmenityRepository implements AmenityRepositoryInterface
{
    protected const PAGE_SIZE = 10;

    public function create(array $data): Amenity
    {
        try {
            return Amenity::create($data);
        } catch (\Exception $e) {
            Log::error("Create Amenity failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?Amenity
    {
        try {
            return Amenity::with(['spaces'])->find($id);
        } catch (\Exception $e) {
            Log::error("Get amenity by id failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): ?Amenity
    {
        try {
            $amenity = Amenity::find($id);

            $amenity->update($data);
            return $amenity;
        } catch (\Exception $e) {
            Log::error("Update Amenity failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $amenity = Amenity::find($id);

            return $amenity->delete();
        } catch (\Exception $e) {
            Log::error("Delete Amenity failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getByVenue(int $venueId, array $filter = [], ?int $pageSize = null)
    {
        try {
            $sortBy = $filter['sortBy'] ?? 'name';
            $sortOrder = $filter['sortOrder'] ?? 'asc';
            $search = $filter['search'] ?? null;
            $pageSize = $pageSize ?? self::PAGE_SIZE;

            $amenities = Amenity::with('spaces')
                                ->where('venue_id', $venueId)
                                ->when($search, function ($query, $search) {
                                    $query->where(function ($q) use ($search) {
                                        $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('code', 'like', "%{$search}%");
                                    });
                                })
                                ->orderBy($sortBy, $sortOrder)
                                ->paginate($pageSize);

            return $amenities;
        } catch (\Exception $e) {
            Log::error("Fetch amenities by venue failed: " . $e->getMessage(), [
                'venue_id' => $venueId,
                'filter'   => $filter,
                'trace'    => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
