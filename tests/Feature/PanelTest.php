<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_guest_access_to_admin_panel_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
    }

    public function test_guest_access_to_guru_panel_is_redirected_to_login(): void
    {
        $response = $this->get('/guru');

        $response->assertStatus(302);
    }

    public function test_guest_access_to_murid_panel_is_redirected_to_login(): void
    {
        $response = $this->get('/murid');

        $response->assertStatus(302);
    }

    public function test_admin_can_only_access_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/guru')->assertStatus(403);
        $this->actingAs($admin)->get('/murid')->assertStatus(403);
    }

    public function test_guru_can_only_access_guru_panel(): void
    {
        $guru = User::factory()->guru()->create();

        $this->actingAs($guru)->get('/guru')->assertStatus(200);
        $this->actingAs($guru)->get('/admin')->assertStatus(403);
        $this->actingAs($guru)->get('/murid')->assertStatus(403);
    }

    public function test_murid_can_only_access_murid_panel(): void
    {
        $murid = User::factory()->murid()->create();

        $this->actingAs($murid)->get('/murid')->assertStatus(200);
        $this->actingAs($murid)->get('/admin')->assertStatus(403);
        $this->actingAs($murid)->get('/guru')->assertStatus(403);
    }

    public function test_admin_can_access_users_management_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
    }
}
