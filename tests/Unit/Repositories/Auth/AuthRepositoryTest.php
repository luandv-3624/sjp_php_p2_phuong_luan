<?php

namespace Tests\Unit\Repositories\Auth;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected AuthRepository $repository;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(AuthRepository::class);
        $this->role = Role::factory()->create();
    }

    /** @test */
    public function it_can_find_an_existing_user()
    {
        $user = User::factory()->create(['role_id' => $this->role->id]);

        $found = $this->repository->findOne($user->id);

        $this->assertEquals($user->id, $found->id);
    }

    /** @test */
    public function it_throws_exception_if_user_not_found()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->findOne(999999);
    }

    /** @test */
    public function it_updates_only_allowed_fields()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone_number' => '123456789',
            'email' => 'unchanged@example.com',
            'role_id' => $this->role->id,
        ]);

        $updated = $this->repository->updateOne($user->id, [
            'name' => 'New Name',
            'phone_number' => '987654321',
            'email' => 'hacked@example.com', // should be ignored
        ]);

        $this->assertEquals('New Name', $updated->name);
        $this->assertEquals('987654321', $updated->phone_number);
        $this->assertEquals('unchanged@example.com', $updated->email); // should remain unchanged
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existing_user()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->updateOne(999999, [
            'name' => 'Does Not Matter',
        ]);
    }
}
