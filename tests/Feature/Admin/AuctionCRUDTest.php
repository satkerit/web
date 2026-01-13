<?php

namespace Tests\Feature\Admin;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuctionCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function admin_can_view_auctions_index()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.index');
    }

    #[Test]
    public function admin_can_view_create_auction_form()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.form');
    }


    #[Test]
    public function admin_can_create_auction_with_valid_data()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Lelang Rumah Test',
            'object_number' => 'OBJ-2025-0001',
            'description' => 'Deskripsi lelang rumah test',
            'asset_type' => 'rumah',
            'certificate_type' => 'SHM',
            'certificate_number' => '12.34.56.78.9.12345',
            'land_area' => 200,
            'building_area' => 150,
            'debtor_name' => 'John Doe',
            'location' => 'Jl. Test No. 123, Jakarta',
            'starting_price' => 500000000,
            'estimated_price' => 600000000,
            'auction_date' => now()->addMonth()->format('Y-m-d'),
            'registration_deadline' => now()->addWeeks(2)->format('Y-m-d'),
            'auction_type' => 'eksekusi',
            'auction_location' => 'Jakarta',
            'deposit_amount' => 100000000,
            'deposit_percentage' => 20,
            'bank_account' => '123-456-7890',
            'bank_name' => 'BRI',
            'account_holder' => 'BPRS Babel',
            'terms_conditions' => 'Syarat dan ketentuan berlaku',
            'viewing_schedule' => 'Setiap hari kerja',
            'kpknl_office' => 'Jakarta',
            'risalah_number' => '1234/2025',
            'status' => 'upcoming',
            'contact_person' => 'Admin Test',
            'contact_phone' => '08123456789',
            'meta_description' => 'Lelang rumah di Jakarta',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.auctions.store'), $data);

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success', 'Lelang berhasil ditambahkan.');

        $this->assertDatabaseHas('auctions', [
            'title' => 'Lelang Rumah Test',
            'object_number' => 'OBJ-2025-0001',
            'asset_type' => 'rumah',
            'status' => 'upcoming',
        ]);
    }

    #[Test]
    public function admin_can_view_edit_auction_form()
    {
        $auction = Auction::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.edit', $auction));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.form');
        $response->assertViewHas('auction');
    }

    #[Test]
    public function admin_can_update_auction()
    {
        Storage::fake('public');

        $auction = Auction::factory()->create([
            'title' => 'Judul Lama',
            'status' => 'upcoming',
        ]);

        $data = [
            'title' => 'Judul Baru Diperbarui',
            'asset_type' => 'ruko',
            'location' => 'Surabaya',
            'starting_price' => 750000000,
            'auction_date' => now()->addMonths(2)->format('Y-m-d'),
            'auction_type' => 'non_eksekusi_wajib',
            'status' => 'ongoing',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.auctions.update', $auction), $data);

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success', 'Lelang berhasil diperbarui.');

        $this->assertDatabaseHas('auctions', [
            'id' => $auction->id,
            'title' => 'Judul Baru Diperbarui',
            'asset_type' => 'ruko',
            'status' => 'ongoing',
        ]);
    }

    #[Test]
    public function admin_can_delete_auction()
    {
        Storage::fake('public');

        $auction = Auction::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->delete(route('admin.auctions.destroy', $auction));

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success', 'Lelang berhasil dihapus.');

        $this->assertDatabaseMissing('auctions', ['id' => $auction->id]);
    }

    #[Test]
    public function title_is_required_when_creating_auction()
    {
        $data = [
            'title' => '',
            'asset_type' => 'rumah',
            'location' => 'Jakarta',
            'starting_price' => 500000000,
            'auction_date' => now()->addMonth()->format('Y-m-d'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.auctions.store'), $data);

        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function location_is_required_when_creating_auction()
    {
        $data = [
            'title' => 'Lelang Test',
            'asset_type' => 'rumah',
            'location' => '',
            'starting_price' => 500000000,
            'auction_date' => now()->addMonth()->format('Y-m-d'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.auctions.store'), $data);

        $response->assertSessionHasErrors('location');
    }

    #[Test]
    public function starting_price_is_required_when_creating_auction()
    {
        $data = [
            'title' => 'Lelang Test',
            'asset_type' => 'rumah',
            'location' => 'Jakarta',
            'starting_price' => '',
            'auction_date' => now()->addMonth()->format('Y-m-d'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.auctions.store'), $data);

        $response->assertSessionHasErrors('starting_price');
    }

    #[Test]
    public function auction_filters_work_correctly()
    {
        Auction::factory()->create(['title' => 'Lelang Rumah Jakarta', 'status' => 'upcoming', 'asset_type' => 'rumah']);
        Auction::factory()->create(['title' => 'Lelang Tanah Bandung', 'status' => 'ongoing', 'asset_type' => 'tanah']);
        Auction::factory()->create(['title' => 'Lelang Ruko Surabaya', 'status' => 'closed', 'asset_type' => 'ruko']);

        // Test search filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.index', ['search' => 'Jakarta']));
        $response->assertStatus(200);
        $response->assertSee('Lelang Rumah Jakarta');
        $response->assertDontSee('Lelang Tanah Bandung');

        // Test status filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.index', ['status' => 'ongoing']));
        $response->assertStatus(200);
        $response->assertSee('Lelang Tanah Bandung');
        $response->assertDontSee('Lelang Rumah Jakarta');

        // Test asset_type filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.auctions.index', ['asset_type' => 'ruko']));
        $response->assertStatus(200);
        $response->assertSee('Lelang Ruko Surabaya');
        $response->assertDontSee('Lelang Rumah Jakarta');
    }
}
