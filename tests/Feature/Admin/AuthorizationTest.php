<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function editor_cannot_access_user_management(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_access_user_management(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_access_user_management(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    #[Test]
    public function editor_can_access_content_management(): void
    {
        $editor = $this->createEditor();

        // Test access to products (content management)
        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function editor_can_access_news_management(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.news.index'));

        $response->assertStatus(200);
    }
}
