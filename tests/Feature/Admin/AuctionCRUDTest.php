<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Auction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuctionCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware that might interfere with tests
        $this->withoutMiddleware([
            \App\Http\Middleware\DdosProtection::class,
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\AdminDdosProtection::class,
            \App\Http\Middleware\CheckMenuPermission::class,
        ]);

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Storage::fake('public');
    }

    public function test_admin_can_view_auctions_index()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.auctions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.index');
    }

    public function test_admin_can_view_create_auction_form()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.auctions.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.form');
    }

    public function test_admin_can_create_auction()
    {
        $this->actingAs($this->admin);

        $data = [
            'title' => 'Test Auction',
            'object_number' => 'OBJ-001',
            'description' => 'Test description',
            'asset_type' => 'rumah',
            'location' => 'Jakarta',
            'starting_price' => 500000000,
            'auction_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
            'contact_person' => 'John Doe',
            'contact_phone' => '08123456789',
        ];

        $response = $this->post(route('admin.auctions.store'), $data);

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('auctions', [
            'title' => 'Test Auction',
            'object_number' => 'OBJ-001',
        ]);
    }

    public function test_admin_can_create_auction_with_images()
    {
        $this->actingAs($this->admin);

        $image1 = UploadedFile::fake()->image('auction1.jpg');
        $image2 = UploadedFile::fake()->image('auction2.jpg');

        $data = [
            'title' => 'Test Auction with Images',
            'asset_type' => 'rumah',
            'location' => 'Jakarta',
            'starting_price' => 500000000,
            'auction_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
            'contact_person' => 'John Doe',
            'contact_phone' => '08123456789',
            'images' => [$image1, $image2],
        ];

        $response = $this->post(route('admin.auctions.store'), $data);

        $response->assertRedirect(route('admin.auctions.index'));

        $auction = Auction::where('title', 'Test Auction with Images')->first();
        $this->assertNotNull($auction);
        $this->assertCount(2, $auction->images);
    }

    public function test_admin_can_view_edit_auction_form()
    {
        $this->actingAs($this->admin);

        $auction = Auction::factory()->create();

        $response = $this->get(route('admin.auctions.edit', $auction));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auctions.form');
        $response->assertViewHas('auction', $auction);
    }

    public function test_admin_can_update_auction()
    {
        $this->actingAs($this->admin);

        $auction = Auction::factory()->create([
            'title' => 'Old Title',
        ]);

        $data = [
            'title' => 'Updated Title',
            'asset_type' => $auction->asset_type,
            'location' => $auction->location,
            'starting_price' => $auction->starting_price,
            'auction_date' => $auction->auction_date->format('Y-m-d\TH:i'),
            'auction_type' => $auction->auction_type,
            'status' => $auction->status,
            'contact_person' => $auction->contact_person,
            'contact_phone' => $auction->contact_phone,
        ];

        $response = $this->put(route('admin.auctions.update', $auction), $data);

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('auctions', [
            'id' => $auction->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_admin_can_update_auction_status_to_sold()
    {
        $this->actingAs($this->admin);

        $auction = Auction::factory()->create([
            'status' => 'ongoing',
        ]);

        $data = [
            'title' => $auction->title,
            'asset_type' => $auction->asset_type,
            'location' => $auction->location,
            'starting_price' => $auction->starting_price,
            'auction_date' => $auction->auction_date->format('Y-m-d\TH:i'),
            'auction_type' => $auction->auction_type,
            'status' => 'sold',
            'winning_bid' => 600000000,
            'winner_name' => 'Jane Doe',
            'sold_at' => now()->format('Y-m-d\TH:i'),
            'contact_person' => $auction->contact_person,
            'contact_phone' => $auction->contact_phone,
        ];

        $response = $this->put(route('admin.auctions.update', $auction), $data);

        $response->assertRedirect(route('admin.auctions.index'));

        $this->assertDatabaseHas('auctions', [
            'id' => $auction->id,
            'status' => 'sold',
            'winning_bid' => 600000000,
            'winner_name' => 'Jane Doe',
        ]);
    }

    public function test_admin_can_delete_auction()
    {
        $this->actingAs($this->admin);

        $auction = Auction::factory()->create();

        $response = $this->delete(route('admin.auctions.destroy', $auction));

        $response->assertRedirect(route('admin.auctions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('auctions', [
            'id' => $auction->id,
        ]);
    }

    public function test_auction_filters_work_correctly()
    {
        $this->actingAs($this->admin);

        Auction::factory()->create(['status' => 'upcoming', 'asset_type' => 'rumah']);
        Auction::factory()->create(['status' => 'ongoing', 'asset_type' => 'tanah']);
        Auction::factory()->create(['status' => 'sold', 'asset_type' => 'rumah']);

        // Filter by status
        $response = $this->get(route('admin.auctions.index', ['status' => 'upcoming']));
        $response->assertStatus(200);

        // Filter by asset_type
        $response = $this->get(route('admin.auctions.index', ['asset_type' => 'rumah']));
        $response->assertStatus(200);

        // Search
        $auction = Auction::factory()->create(['title' => 'Unique Auction Title']);
        $response = $this->get(route('admin.auctions.index', ['search' => 'Unique']));
        $response->assertStatus(200);
        $response->assertSee('Unique Auction Title');
    }

    public function test_validation_requires_mandatory_fields()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.auctions.store'), []);

        $response->assertSessionHasErrors([
            'title',
            'asset_type',
            'location',
            'starting_price',
            'auction_date',
            'auction_type',
            'status',
        ]);
    }

    public function test_all_status_values_are_valid()
    {
        $this->actingAs($this->admin);

        $statuses = ['upcoming', 'ongoing', 'closed', 'sold', 'cancelled'];

        foreach ($statuses as $status) {
            $auction = Auction::factory()->create(['status' => $status]);
            $this->assertEquals($status, $auction->status);
        }
    }

    public function test_auction_slug_is_generated_automatically()
    {
        $this->actingAs($this->admin);

        $data = [
            'title' => 'Test Auction for Slug',
            'asset_type' => 'rumah',
            'location' => 'Jakarta',
            'starting_price' => 500000000,
            'auction_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'auction_type' => 'eksekusi',
            'status' => 'upcoming',
            'contact_person' => 'John Doe',
            'contact_phone' => '08123456789',
        ];

        $this->post(route('admin.auctions.store'), $data);

        $auction = Auction::where('title', 'Test Auction for Slug')->first();
        $this->assertNotNull($auction->slug);
        $this->assertEquals('test-auction-for-slug', $auction->slug);
    }
}
