<?php

namespace App\Services\User;

use App\Helpers\ApiResponse;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserService implements UserServiceInterface
{

    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function findAll(array $filter, ?int $pageSize): JsonResponse
    {
        return ApiResponse::success(new UserCollection($this->userRepo->findAll($filter, $pageSize)));
    }

    public function updateOne(int $id, array $data): JsonResponse
    {
        return ApiResponse::success(new UserResource($this->userRepo->updateOne($id, $data)));
    }
}
