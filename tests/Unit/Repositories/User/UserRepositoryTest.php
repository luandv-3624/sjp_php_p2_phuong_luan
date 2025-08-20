<?php

namespace Tests\Unit\Repositories\User;

use App\Enums\SortOrder;
use App\Enums\UsersSortBy;
use App\Models\Role;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new UserRepository();
        $this->role = Role::factory()->create();
    }

    /** @test */
    public function it_returns_paginated_users()
    {
        User::factory()->count(15)->create(['role_id' => $this->role->id]);

        $result = $this->repo->findAll([], 10);

        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    /** @test */
    public function it_can_filter_by_name_or_email()
    {
        $user1 = User::factory()->create([
            'name' => 'Alice Example',
            'email' => 'alice@test.com',
            'role_id' => $this->role->id
        ]);

        $user2 = User::factory()->create([
            'name' => 'Bob Example',
            'email' => 'bob@test.com',
            'role_id' => $this->role->id
        ]);

        $result = $this->repo->findAll(['search' => 'alice'], 10);

        $this->assertTrue($result->contains('id', $user1->id));
        $this->assertFalse($result->contains('id', $user2->id));
    }

    /** @test */
    public function it_can_sort_users_by_email_desc()
    {
        $userA = User::factory()->create([
            'email' => 'a@example.com',
            'role_id' => $this->role->id
        ]);

        $userZ = User::factory()->create([
            'email' => 'z@example.com',
            'role_id' => $this->role->id
        ]);

        $result = $this->repo->findAll([
            'sortBy' => UsersSortBy::EMAIL->value,
            'sortOrder' => SortOrder::DESC->value
        ], 10);

        $emails = $result->pluck('email')->toArray();

        $this->assertEquals(['z@example.com', 'a@example.com'], $emails);
    }

    /** @test */
    public function it_uses_default_page_size_when_per_page_is_null()
    {
        $defaultPageSize = (new \ReflectionClass(UserRepository::class))
            ->getConstant('PAGE_SIZE');

        User::factory()->count($defaultPageSize + 5)->create([
            'role_id' => $this->role->id
        ]);

        $result = $this->repo->findAll([], null);

        $this->assertEquals($defaultPageSize, $result->perPage());
        $this->assertEquals($defaultPageSize + 5, $result->total());
    }

    /** @test */
    public function it_falls_back_to_default_sorting_when_invalid_values_are_passed()
    {
        $userA = User::factory()->create([
            'name' => 'Bob',
            'role_id' => $this->role->id
        ]);

        $userB = User::factory()->create([
            'name' => 'Alice',
            'role_id' => $this->role->id
        ]);

        $result = $this->repo->findAll([
            'sortBy' => 'invalid_column',
            'sortOrder' => 'not_a_direction',
        ], 10);

        // fallback = sortBy NAME ASC
        $names = $result->pluck('name')->toArray();

        $this->assertEquals(['Alice', 'Bob'], $names);
    }
}
