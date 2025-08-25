<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Role;
use App\Enums\Role as EnumsRole;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy();
    }

    /** @test */
    public function normal_user_cannot_update_anyone()
    {
        $user = User::factory()->create(['role_id' => Role::factory()->create(['name' => EnumsRole::USER])->id]);
        $target = User::factory()->create(['role_id' => Role::factory()->create(['name' => EnumsRole::USER])->id]);

        $this->assertFalse($this->policy->update($user, $target));
    }

    /** @test */
    public function user_cannot_update_themselves()
    {
        $role = Role::factory()->create(['name' => EnumsRole::USER]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertFalse($this->policy->update($user, $user));
    }

    /** @test */
    public function admin_cannot_update_another_admin()
    {
        $adminRole = Role::factory()->create(['name' => EnumsRole::ADMIN]);

        $admin1 = User::factory()->create(['role_id' => $adminRole->id]);
        $admin2 = User::factory()->create(['role_id' => $adminRole->id]);

        $this->assertFalse($this->policy->update($admin1, $admin2));
    }

    /** @test */
    public function admin_can_update_moderator()
    {
        $adminRole = Role::factory()->create(['name' => EnumsRole::ADMIN]);
        $moderatorRole = Role::factory()->create(['name' => EnumsRole::MODERATOR]);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $moderator = User::factory()->create(['role_id' => $moderatorRole->id]);

        $this->assertTrue($this->policy->update($admin, $moderator));
    }

    /** @test */
    public function moderator_cannot_update_moderator()
    {
        $moderatorRole = Role::factory()->create(['name' => EnumsRole::MODERATOR]);

        $mod1 = User::factory()->create(['role_id' => $moderatorRole->id]);
        $mod2 = User::factory()->create(['role_id' => $moderatorRole->id]);

        $this->assertFalse($this->policy->update($mod1, $mod2));
    }
}
