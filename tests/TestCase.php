<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Roles and Permissions first
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        // Clear cache to reset rate limiters for each test
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        // Seed AdminMenu if table exists to ensure RBAC works in tests
        if (\Illuminate\Support\Facades\Schema::hasTable('admin_menus')) {
            $this->seed(\Database\Seeders\AdminMenuSeeder::class);
        }
    }

    /**
     * Create an admin user.
     */
    protected function createAdmin(): User
    {
        return User::factory()->admin()->create([
            'is_active' => true,
        ]);
    }

    /**
     * Create a super admin user.
     */
    protected function createSuperAdmin(): User
    {
        return User::factory()->superAdmin()->create([
            'is_active' => true,
        ]);
    }

    /**
     * Create an editor user.
     */
    protected function createEditor(): User
    {
        return User::factory()->editor()->create([
            'is_active' => true,
        ]);
    }

    /**
     * Disable security middleware for testing.
     */
    protected function withoutSecurityMiddleware(): static
    {
        return $this->withoutMiddleware([
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\LogVisitor::class,
            \App\Http\Middleware\OptimizeResponse::class,
        ]);
    }
}
