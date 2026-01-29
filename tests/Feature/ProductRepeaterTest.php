<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductRepeaterTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and admin menus
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\AdminMenuSeeder::class);

        $adminRoleId = Role::where('name', 'admin')->value('id');
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'role_id' => $adminRoleId,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_can_save_json_repeater_fields()
    {
        $data = [
            'name' => 'Repeater Test Product',
            'type' => 'simpanan_syariah',
            'description' => 'Test Description',
            // Simulasi data yang dikirim oleh form repeater
            'features' => ['Fitur 1', 'Fitur 2', 'Fitur "Quote" 3'],
            'benefits' => ['Keunggulan 1', 'Keunggulan 2'],
            'requirements' => ['KTP', 'NPWP'],
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Repeater Test Product',
        ]);

        $product = \App\Models\Product::where('name', 'Repeater Test Product')->first();

        // Pastikan tersimpan sebagai JSON array yang benar
        $this->assertEquals(['Fitur 1', 'Fitur 2', 'Fitur "Quote" 3'], $product->features);
        $this->assertEquals(['Keunggulan 1', 'Keunggulan 2'], $product->benefits);
        $this->assertEquals(['KTP', 'NPWP'], $product->requirements);
    }
}
