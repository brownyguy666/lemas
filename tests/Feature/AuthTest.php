<?php

namespace Tests\Feature;

use App\Http\Responses\LoginResponse;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_all_expected_users(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@lemas.sch.id',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'budi.guru@lemas.sch.id',
            'role' => User::ROLE_GURU,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'siti.guru@lemas.sch.id',
            'role' => User::ROLE_GURU,
        ]);

        $this->assertDatabaseCount('users', 13);
    }

    public function test_login_response_redirects_admin_to_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = (new LoginResponse)->toResponse(Request::create('/login', 'POST'));

        $this->assertEquals(url('/admin'), $response->getTargetUrl());
    }

    public function test_login_response_redirects_guru_to_guru_panel(): void
    {
        $guru = User::factory()->guru()->create();
        $this->actingAs($guru);

        $response = (new LoginResponse)->toResponse(Request::create('/login', 'POST'));

        $this->assertEquals(url('/guru'), $response->getTargetUrl());
    }

    public function test_login_response_redirects_murid_to_murid_panel(): void
    {
        $murid = User::factory()->murid()->create();
        $this->actingAs($murid);

        $response = (new LoginResponse)->toResponse(Request::create('/login', 'POST'));

        $this->assertEquals(url('/murid'), $response->getTargetUrl());
    }

    public function test_admin_policy_authorizes_user_management(): void
    {
        $admin = User::factory()->admin()->create();
        $guru = User::factory()->guru()->create();
        $murid = User::factory()->murid()->create();

        $this->assertTrue($admin->can('viewAny', User::class));
        $this->assertTrue($admin->can('create', User::class));
        $this->assertTrue($admin->can('delete', $guru));
        // Admin cannot delete self
        $this->assertFalse($admin->can('delete', $admin));

        $this->assertFalse($guru->can('viewAny', User::class));
        $this->assertFalse($guru->can('create', User::class));

        $this->assertFalse($murid->can('viewAny', User::class));
        $this->assertFalse($murid->can('create', User::class));
    }
}
