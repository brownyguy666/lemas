<?php

namespace Tests\Feature;

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

    public function test_authenticated_user_can_access_panels(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertStatus(200);
        $this->actingAs($user)->get('/guru')->assertStatus(200);
        $this->actingAs($user)->get('/murid')->assertStatus(200);
    }
}
