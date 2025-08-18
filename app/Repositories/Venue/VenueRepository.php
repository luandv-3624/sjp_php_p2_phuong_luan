<?php

namespace App\Repositories\Venue;

use App\Models\Venue;
use Illuminate\Support\Facades\Log;

class VenueRepository implements VenueRepositoryInterface
{
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
