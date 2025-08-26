<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\UpdateStatusRequest;
use App\Models\User;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{

    public function __construct(private UserServiceInterface $userService) {}

    public function index(IndexRequest $request): JsonResponse
    {
        $query = $request->validated();
        $filter = [
            'sortBy' => $query['sortBy'] ?? null,
            'sortOrder' => $query['sortOrder'] ?? null,
            'search' => $query['search'] ?? null,
        ];
        $perPage = $query['perPage'] ?? null;

        return $this->userService->findAll($filter, $perPage);
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        return $this->userService->updateOne($user->id, $data);
    }

    public function updateStatus(UpdateStatusRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        return $this->userService->updateOne($user->id, $data);
    }
}
