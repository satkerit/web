<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and admin menus
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\AdminMenuSeeder::class);

        // Create admin user with proper role_id and active status
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
    public function admin_can_view_products_index()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    #[Test]
    public function admin_can_view_create_product_form()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    #[Test]
    public function admin_can_create_product()
    {
        Storage::fake('public');

        $data = [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            'short_description' => 'Short description test',
            'description' => 'Full description test',
            'interest_rate' => '3% - 5%',
            'features' => ['Feature 1', 'Feature 2'],
            'requirements' => ['Requirement 1'],
            'benefits' => ['Benefit 1', 'Benefit 2'],
            'image' => UploadedFile::fake()->image('product.jpg'),
            'image_alt' => 'Test Product Image',
            'is_active' => true,
            'order_position' => 1,
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
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan.');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            'short_description' => 'Short description test',
            'description' => 'Full description test',
            'is_active' => true,
            'order_position' => 1,
        ]);

        $product = Product::first();
        if ($product && $product->image) {
            Storage::disk('public')->assertExists('products/' . basename($product->image));
        }
    }

    #[Test]
    public function admin_can_view_edit_product_form()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('product');
    }

    #[Test]
    public function admin_can_update_product()
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'description' => 'Old description',
        ]);

        $data = [
            'name' => 'Updated Product Name',
            'type' => 'pembiayaan_syariah',
            'short_description' => 'Updated short description',
            'description' => 'Updated full description',
            'interest_rate' => '4% - 6%',
            'features' => ['Updated Feature 1'],
            'requirements' => ['Updated Requirement 1'],
            'benefits' => ['Updated Benefit 1'],
            'is_active' => false,
            'order_position' => 5,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Produk berhasil diperbarui.');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'type' => 'pembiayaan_syariah',
            'description' => 'Updated full description',
            'is_active' => false,
            'order_position' => 5,
        ]);
    }

    #[Test]
    public function admin_can_delete_product()
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'image' => 'products/test-image.jpg',
        ]);

        Storage::disk('public')->put('products/test-image.jpg', 'fake-image-content');

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Produk berhasil dihapus.');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    #[Test]
    public function description_is_required_when_creating_product()
    {
        $data = [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            // description is missing
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.products.store'), $data);

        $response->assertSessionHasErrors('description');
    }

    #[Test]
    public function product_filters_work_correctly()
    {
        Product::factory()->create(['name' => 'Tabungan Test', 'type' => 'simpanan_syariah']);
        Product::factory()->create(['name' => 'Pembiayaan Test', 'type' => 'pembiayaan_syariah']);
        Product::factory()->create(['name' => 'Deposito Test', 'type' => 'deposito']);

        // Test search filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.products.index', ['search' => 'Tabungan']));
        $response->assertStatus(200);
        $response->assertSee('Tabungan Test');
        $response->assertDontSee('Pembiayaan Test');

        // Test type filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.products.index', ['type' => 'deposito']));
        $response->assertStatus(200);
        $response->assertSee('Deposito Test');
        $response->assertDontSee('Tabungan Test');
    }
}
