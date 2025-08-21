<?php

namespace App\Services\User;

use Illuminate\Http\JsonResponse;

interface UserServiceInterface
{
    public function findAll(array $filter, ?int $pageSize): JsonResponse;

    public function updateOne(int $id, array $data): JsonResponse;
}
