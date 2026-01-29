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

        // Clear cache to reset rate limiters
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

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
        $response = $this->withSession(['login_captcha_answer' => 10])
            ->post('/login', [
                'email' => 'admin@test.com',
                'password' => 'password123',
                'captcha_answer' => '10',
            ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->withSession(['login_captcha_answer' => 10])
            ->from('/login')
            ->post('/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong-password',
                'captcha_answer' => '10',
            ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_users_cannot_authenticate_with_invalid_email(): void
    {
        $response = $this->withSession(['login_captcha_answer' => 10])
            ->from('/login')
            ->post('/login', [
                'email' => 'nonexistent@test.com',
                'password' => 'password123',
                'captcha_answer' => '10',
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

        $response = $this->withSession(['login_captcha_answer' => 10])
            ->from('/login')
            ->post('/login', [
                'email' => 'inactive@test.com',
                'password' => 'password123',
                'captcha_answer' => '10',
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
        // Set CAPTCHA answer in session
        session(['login_captcha_answer' => 10]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'remember' => true,
            'captcha_answer' => '10',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_rate_limiting_on_login(): void
    {
        // Attempt login 5 times with wrong password
        for ($i = 0; $i < 5; $i++) {
            $this->withSession(['login_captcha_answer' => 10])
                ->from('/login')
                ->post('/login', [
                    'email' => 'admin@test.com',
                    'password' => 'wrong-password',
                    'captcha_answer' => '10',
                ]);
        }

        // 6th attempt should be rate limited by LoginRequest (limit is 5)
        $response = $this->withSession(['login_captcha_answer' => 10])
            ->from('/login')
            ->post('/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong-password',
                'captcha_answer' => '10',
            ]);

        if (!session()->has('errors')) {
            dump('Rate limit test failed debug info:');
            dump('Status Code: ' . $response->status());
            dump('Content snippet: ' . substr($response->getContent(), 0, 200));
            dump('Session Data: ', session()->all());
        }

        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertNotNull($errors);
        $emailError = $errors->first('email');
        // Match either English or Indonesian message
        $this->assertTrue(
            str_contains($emailError, 'Too many') || str_contains($emailError, 'Terlalu banyak'),
            "Expected error message to contain 'Too many' or 'Terlalu banyak', got: " . $emailError
        );
    }
}
