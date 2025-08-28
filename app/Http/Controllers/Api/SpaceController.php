<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Space\IndexRequest;
use App\Http\Requests\Space\StoreRequest;
use App\Http\Requests\Space\UpdateRequest;
use App\Models\Space;
use App\Models\Venue;
use App\Services\Space\SpaceServiceInterface;
use Illuminate\Http\JsonResponse;

class SpaceController extends BaseApiController
{
    public function __construct(private SpaceServiceInterface $spaceService) {}

    public function store(StoreRequest $request, Venue $venue): JsonResponse
    {
        return $this->spaceService->create([
            'venue_id' => $venue->id,
            ...$request->validated(),
        ]);
    }

    public function update(UpdateRequest $request, Space $space): JsonResponse
    {
        return $this->spaceService->updateById($space->id, $request->validated());
    }

    public function show(Space $space): JsonResponse
    {
        return $this->spaceService->findById($space->id);
    }

    public function indexByVenue(Venue $venue): JsonResponse
    {
        return $this->spaceService->findAllByVenue($venue->id);
    }

    public function index(IndexRequest $request): JsonResponse
    {
        return $this->spaceService->findAll($request->validated(), $request->get('pageSize'));
    }
}
