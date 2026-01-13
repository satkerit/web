<?php

namespace Tests\Feature\Security;

use App\Models\SiteSetting;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear cache before each test
        SiteSetting::clearCache();
    }

    #[Test]
    public function public_routes_show_maintenance_page_when_enabled(): void
    {
        // Enable maintenance mode on existing or new settings
        $settings = SiteSetting::first() ?? new SiteSetting();
        $settings->fill([
            'maintenance_mode' => true,
            'maintenance_message' => 'Website sedang dalam pemeliharaan.',
        ]);
        $settings->save();
        SiteSetting::clearCache();

        $response = $this->get('/');

        $response->assertStatus(503);
        $response->assertViewIs('errors.503');
    }

    #[Test]
    public function admin_routes_remain_accessible_during_maintenance(): void
    {
        // Enable maintenance mode on existing or new settings
        $settings = SiteSetting::first() ?? new SiteSetting();
        $settings->fill([
            'maintenance_mode' => true,
            'maintenance_message' => 'Website sedang dalam pemeliharaan.',
        ]);
        $settings->save();
        SiteSetting::clearCache();

        $admin = $this->createAdmin();

        // Admin dashboard should be accessible (redirects to login if not authenticated)
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        // Should not return 503 - admin routes bypass maintenance mode
        $this->assertNotEquals(503, $response->getStatusCode());
    }
}
