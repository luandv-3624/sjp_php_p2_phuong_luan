<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Role;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function verifyUser(int $userId): bool;
    public function getRoleByName(string $roleName): ?Role;
}
