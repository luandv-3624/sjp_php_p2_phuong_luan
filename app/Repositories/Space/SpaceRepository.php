<?php

namespace App\Repositories\Space;

use App\Models\Space;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpaceRepository implements SpaceRepositoryInterface
{
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
        $space = Space::with(['type', 'priceType'])->find($id);

        if (!$space) {
            throw new NotFoundHttpException(__('space.space_not_found'));
        }

        return $space;
    }

    public function findAllByVenue(int $venueId): Collection
    {
        return Space::with(['type', 'priceType'])->where('venue_id', $venueId)->orderBy('created_at', 'desc')->get();
    }
}
