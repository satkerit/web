<?php

namespace Tests\Feature\Admin;

use App\Models\FinancingConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature tests for Admin Financing Config management.
 *
 * **Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.7**
 */
class FinancingConfigTest extends TestCase
{
    use RefreshDatabase;

    private FinancingConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test financing config
        $this->config = FinancingConfig::create([
            'type' => 'murabahah',
            'name' => 'Pembiayaan Murabahah',
            'margin_rate' => 0.12,
            'min_principal' => 5000000,
            'max_principal' => 500000000,
            'available_tenors' => [12, 24, 36, 48, 60],
            'is_active' => true,
        ]);
    }

    #[Test]
    public function admin_can_view_financing_config_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.financing-config.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.financing-config.index');
        $response->assertSee('Pembiayaan Murabahah');
    }

    #[Test]
    public function super_admin_can_view_financing_config_list(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.financing-config.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.financing-config.index');
    }

    #[Test]
    public function admin_can_update_financing_config(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.financing-config.update', $this->config), [
                'name' => 'Updated Murabahah',
                'margin_rate' => 15, // 15%
                'min_principal' => 10000000,
                'max_principal' => 600000000,
                'available_tenors' => [12, 24, 36],
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.financing-config.index'));
        $response->assertSessionHas('success');

        $this->config->refresh();
        $this->assertEquals('Updated Murabahah', $this->config->name);
        $this->assertEquals(0.15, (float) $this->config->margin_rate);
        $this->assertEquals(10000000, $this->config->min_principal);
        $this->assertEquals(600000000, $this->config->max_principal);
        $this->assertEquals([12, 24, 36], $this->config->available_tenors);
    }

    #[Test]
    public function validation_error_when_margin_rate_is_not_positive(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.financing-config.update', $this->config), [
                'name' => 'Test Config',
                'margin_rate' => 0,
                'min_principal' => 5000000,
                'max_principal' => 500000000,
                'available_tenors' => [12, 24],
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors('margin_rate');
    }

    #[Test]
    public function validation_error_when_max_principal_not_greater_than_min(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.financing-config.update', $this->config), [
                'name' => 'Test Config',
                'margin_rate' => 0.12,
                'min_principal' => 100000000,
                'max_principal' => 50000000, // Less than min
                'available_tenors' => [12, 24],
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors('max_principal');
    }

    #[Test]
    public function editor_cannot_access_financing_config(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.financing-config.index'));

        $response->assertStatus(403);
    }

    #[Test]
    public function editor_cannot_update_financing_config(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->put(route('admin.financing-config.update', $this->config), [
                'name' => 'Hacked Config',
                'margin_rate' => 0.50,
                'min_principal' => 1000,
                'max_principal' => 1000000000,
                'available_tenors' => [12],
                'is_active' => true,
            ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_financing_config(): void
    {
        $response = $this->withoutSecurityMiddleware()
            ->get(route('admin.financing-config.index'));

        $response->assertRedirect(route('login'));
    }
}
