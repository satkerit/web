<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->withoutSecurityMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    #[Test]
    public function authenticated_editor_can_access_dashboard(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    #[Test]
    public function inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    #[Test]
    public function inactive_user_is_logged_out_when_accessing_admin(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)
            ->withoutSecurityMiddleware()
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
