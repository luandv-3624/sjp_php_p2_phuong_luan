<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function verifyUser(int $userId): bool;
    public function getRoleByName(string $roleName): ?Role;
    public function updatePassword(int $id, string $password): bool;
    public function findAll(array $filter, ?int $pageSize): LengthAwarePaginator;
    public function updateOne(int $id, array $data): User;
}
