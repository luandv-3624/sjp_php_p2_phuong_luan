<?php

namespace App\Services\User;

use Illuminate\Http\JsonResponse;

interface UserServiceInterface
{
    public function findAll(array $filter, ?int $pageSize): JsonResponse;
}
