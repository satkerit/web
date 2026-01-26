<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable middleware that might interfere with tests
        $this->withoutMiddleware([
            \App\Http\Middleware\DdosProtection::class,
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\AdminDdosProtection::class,
        ]);
        
        // Create a test user
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Admin Login');
        $response->assertSee('csrf-token', false);
    }

    public function test_users_can_authenticate_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_users_cannot_authenticate_with_invalid_email(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_users_cannot_login(): void
    {
        // Create inactive user
        User::factory()->create([
            'email' => 'inactive@test.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'inactive@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
    }

    public function test_csrf_token_is_present_on_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('csrf-token', false);
        $response->assertSee('_token', false);
    }

    public function test_remember_me_functionality(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_rate_limiting_on_login(): void
    {
        // Attempt login 6 times with wrong password
        for ($i = 0; $i < 6; $i++) {
            $this->from('/login')->post('/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong-password',
            ]);
        }

        // 7th attempt should be rate limited
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $errors = $response->getSession()->get('errors');
        $this->assertNotNull($errors);
        $emailError = $errors->first('email');
        $this->assertStringContainsString('Too many', $emailError);
    }
}
