<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Hash;
use App\Enums\SortOrder;
use App\Enums\UsersSortBy;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Enums\AccountStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public const PAGE_SIZE = 10;

    public function findByEmail(string $email): ?User
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Exception $e) {
            Log::error('Find by email failed: '.$e->getMessage(), [
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create(array $data): User
    {
        try {
            return User::create($data);
        } catch (\Exception $e) {
            Log::error('User creation failed: '.$e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function verifyUser(int $userId): bool
    {
        try {
            return User::where('id', $userId)->update([
                'status'            => AccountStatus::VERIFIED,
                'email_verified_at' => now(),
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('User verification failed: '.$e->getMessage(), [
                'user_id' => $userId,
                'trace'   => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getRoleByName(string $roleName): ?Role
    {
        try {
            return Role::where('name', $roleName)->first();
        } catch (\Exception $e) {
            Log::error('Get role by name failed: '.$e->getMessage(), [
                'role_name' => $roleName,
                'trace'     => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updatePassword(int $id, string $password): bool
    {
        try {
            return User::where('id', $id)->update([
                                        'password' => Hash::make($password),
                    ]);
        } catch (\Exception $e) {
            Log::error('Update failed: '.$e->getMessage(), [
                'id'    => $id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findAll(array $filter, ?int $pageSize): LengthAwarePaginator
    {
        try {
            $sortBy = UsersSortBy::tryFrom($filter['sortBy'] ?? null) ?? UsersSortBy::NAME;
            $sortOrder = SortOrder::tryFrom($filter['sortOrder'] ?? null) ?? SortOrder::ASC;
            $search = $filter['search'] ?? null;
            $perPage = $pageSize ?? self::PAGE_SIZE;

            $users = User::with('role')
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->orderBy($sortBy->value, $sortOrder->value)
                ->paginate($perPage);

            return $users;
        } catch (\Exception $e) {
            Log::error('Fetch all users failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
