<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\IndexRequest;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{

    public function __construct(private UserServiceInterface $authService) {}

    public function currentUser(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ], 200);
    }

    public function index(IndexRequest $request): JsonResponse
    {
        $query = $request->validated();
        $filter = [
            'sortBy' => $query['sortBy'] ?? null,
            'sortOrder' => $query['sortOrder'] ?? null,
            'search' => $query['search'] ?? null,
        ];
        $perPage = $query['perPage'] ?? null;

        return $this->authService->findAll($filter, $perPage);
    }
}
