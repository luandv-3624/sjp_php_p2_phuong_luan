<?php

namespace Tests\Unit\Repositories\User;

use App\Enums\AccountStatus;
use App\Enums\Role as EnumsRole;
use App\Models\Role;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Tests\TestCase;

class UserRepositoryUpdateOneTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;

    protected Role $userRole;
    protected Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new UserRepository();
        $this->userRole = Role::factory()->create(['name' => EnumsRole::USER]);
        $this->adminRole = Role::factory()->create(['name' => EnumsRole::ADMIN]);
    }

    /** @test */
    public function it_can_activate_an_inactive_user()
    {
        $user = User::factory()->create([
            'status' => AccountStatus::INACTIVE->value,
            'role_id' => $this->userRole->id,
        ]);

        $updated = $this->repo->updateOne($user->id, ['status' => AccountStatus::ACTIVE->value]);

        $this->assertEquals(AccountStatus::ACTIVE->value, $updated->status);
    }

    /** @test */
    public function it_throws_conflict_when_activating_already_active_user()
    {
        $user = User::factory()->create([
            'status' => AccountStatus::ACTIVE->value,
            'role_id' => $this->userRole->id,
        ]);

        $this->expectException(ConflictHttpException::class);

        $this->repo->updateOne($user->id, ['status' => AccountStatus::ACTIVE->value]);
    }

    /** @test */
    public function it_can_deactivate_active_user_and_delete_tokens()
    {
        $user = User::factory()->create([
            'status' => AccountStatus::ACTIVE->value,
            'role_id' => $this->userRole->id,
        ]);

        // fake a token
        $user->tokens()->create([
            'name' => 'test',
            'token' => 'fake-token',
            'abilities' => ['*'],
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $updated = $this->repo->updateOne($user->id, ['status' => AccountStatus::INACTIVE->value]);

        $this->assertEquals(AccountStatus::INACTIVE->value, $updated->status);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    /** @test */
    public function it_throws_conflict_when_deactivating_already_inactive_user()
    {
        $user = User::factory()->create([
            'status' => AccountStatus::INACTIVE->value,
            'role_id' => $this->userRole->id,
        ]);

        $this->expectException(ConflictHttpException::class);

        $this->repo->updateOne($user->id, ['status' => AccountStatus::INACTIVE->value]);
    }

    /** @test */
    public function it_can_verify_user_email()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'role_id' => $this->userRole->id,
        ]);

        $updated = $this->repo->updateOne($user->id, ['status' => AccountStatus::VERIFIED->value]);

        $this->assertNotNull($updated->email_verified_at);
    }

    /** @test */
    public function it_throws_conflict_when_verifying_already_verified_user()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'role_id' => $this->userRole->id,
        ]);

        $this->expectException(ConflictHttpException::class);

        $this->repo->updateOne($user->id, ['status' => AccountStatus::VERIFIED->value]);
    }

    /** @test */
    public function it_can_update_user_role()
    {
        $user = User::factory()->create([
            'role_id' => $this->userRole->id,
        ]);

        $newRole = Role::factory()->create();

        $updated = $this->repo->updateOne($user->id, ['role_id' => $newRole->id]);

        $this->assertEquals($newRole->id, $updated->role_id);
    }

    /** @test */
    public function it_cannot_change_admin_role()
    {
        $user = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $otherRole = Role::factory()->create();

        $this->expectException(ConflictHttpException::class);

        $this->repo->updateOne($user->id, ['role_id' => $otherRole->id]);
    }

    /** @test */
    public function it_throws_exception_if_user_does_not_exist()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repo->updateOne(999999, [
            'status' => AccountStatus::ACTIVE->value,
        ]);
    }
}
