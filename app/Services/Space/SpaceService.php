<?php

namespace App\Services\Space;

use App\Helpers\ApiResponse;
use App\Http\Resources\Space\SpaceCollection;
use App\Http\Resources\Space\SpaceResource;
use App\Repositories\Space\SpaceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SpaceService implements SpaceServiceInterface
{
    public function __construct(private SpaceRepositoryInterface $spaceRepo) {}

    public function create(array $data): JsonResponse
    {
        return ApiResponse::success(new SpaceResource($this->spaceRepo->create($data)), __('space.space_created'), Response::HTTP_CREATED);
    }

    public function updateById(int $id, array $data): JsonResponse
    {
        return ApiResponse::success(new SpaceResource($this->spaceRepo->updateById($id, $data)), __('space.space_updated'));
    }

    public function findById(int $id): JsonResponse
    {
        return ApiResponse::success(new SpaceResource($this->spaceRepo->findById($id)));
    }

    public function findAllByVenue(int $venueId): JsonResponse
    {
        return ApiResponse::success(SpaceResource::collection($this->spaceRepo->findAllByVenue($venueId)));
    }

    public function findAll(array $filters, ?int $pageSize): JsonResponse
    {
        return ApiResponse::success(new SpaceCollection($this->spaceRepo->findAll($filters, $pageSize)));
    }
}
