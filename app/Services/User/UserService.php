<?php

namespace App\Services\User;

use App\Helpers\ApiResponse;
use App\Http\Resources\User\UserCollection;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserService implements UserServiceInterface
{

    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function findAll(array $filter, ?int $pageSize): JsonResponse
    {
        return ApiResponse::success(new UserCollection($this->userRepo->findAll($filter, $pageSize)));
    }
}
