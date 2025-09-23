<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Hash;
use App\Enums\SortOrder;
use App\Enums\UsersSortBy;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Enums\AccountStatus;
use App\Enums\Role as EnumsRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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

    private function updateUserStatus(User $user, string $status): User
    {
        try {
            $validStatus = AccountStatus::from($status);

            switch ($validStatus) {
                case AccountStatus::ACTIVE: {
                    if ($user->status === AccountStatus::ACTIVE->value) {
                        throw new ConflictHttpException(__('users.already_active'));
                    }

                    $user->status = AccountStatus::ACTIVE->value;
                    $user->save();

                    break;
                }
                case AccountStatus::INACTIVE: {
                    if ($user->status === AccountStatus::INACTIVE->value) {
                        throw new ConflictHttpException(__('users.already_inactive'));
                    }

                    $user->status = AccountStatus::INACTIVE->value;
                    $user->tokens()->delete();
                    $user->save();

                    break;
                }
                case AccountStatus::VERIFIED: {
                    if (isset($user->email_verified_at)) {
                        throw new ConflictHttpException(__('users.already_verified'));
                    }

                    $user->email_verified_at = now();
                    $user->save();

                    break;
                }
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('Update status failed: ' . $e->getMessage(), [
                'id' => $user->id,
                'status' => $status,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function updateUserRole(User $user, int $roleId): User
    {
        try {
            if ($user->role->name === EnumsRole::ADMIN) {
                throw new ConflictHttpException(__('users.cannot_change_admin_role'));
            }

            $role = Role::findOrFail($roleId);

            $user->role()->associate($role);
            $user->save();

            return $user;
        } catch (\Exception $e) {
            Log::error('Update role failed: ' . $e->getMessage(), [
                'id' => $user->id,
                'roleId' => $roleId,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateOne(int $id, array $data): User
    {
        $user = User::with('role')->findOrFail($id);

        if (isset($data['status'])) {
            $user = $this->updateUserStatus($user, $data['status']);
        }

        if (isset($data['role_id'])) {
            $user = $this->updateUserRole($user, $data['role_id']);
        }

        return $user;
    }

    public function findAllSimple(): Collection
    {
        return User::select('id', 'name', 'email')->orderBy('name')->get();
    }
}
