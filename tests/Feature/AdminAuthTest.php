<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_login_page_shows(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Login Panel Admin');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'password']);

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'password']);

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_cannot_login(): void
    {
        $user = User::factory()->create(['role' => 'user', 'password' => 'password']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_user_gets_403_on_admin_pages(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/products')->assertForbidden();
        $this->actingAs($user)->get('/admin/pemasukan')->assertForbidden();
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
