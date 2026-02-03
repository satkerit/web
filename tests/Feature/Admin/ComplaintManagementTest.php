<?php

namespace Tests\Feature\Admin;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ComplaintManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();
    }

    #[Test]
    public function admin_can_view_complaints_index()
    {
        Complaint::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.complaints.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.complaints.index');
        $response->assertViewHas('complaints');
    }

    #[Test]
    public function admin_can_view_complaint_detail()
    {
        $complaint = Complaint::factory()->create([
            'subject' => 'Test Complaint Subject',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.complaints.show', $complaint));

        $response->assertStatus(200);
        $response->assertViewIs('admin.complaints.show');
        $response->assertViewHas('complaint');
        $response->assertSee('Test Complaint Subject');
    }

    #[Test]
    public function admin_can_update_complaint_status()
    {
        $complaint = Complaint::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.complaints.update', $complaint), [
                'status' => 'in_review',
                'admin_notes' => null,
            ]);

        $response->assertRedirect(route('admin.complaints.show', $complaint));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'in_review',
        ]);
    }

    #[Test]
    public function admin_can_add_notes_to_complaint()
    {
        $complaint = Complaint::factory()->create([
            'status' => 'pending',
            'admin_notes' => null,
        ]);

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.complaints.update', $complaint), [
                'status' => 'investigating',
                'admin_notes' => 'Sedang dalam proses investigasi oleh tim.',
            ]);

        $response->assertRedirect(route('admin.complaints.show', $complaint));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'investigating',
            'admin_notes' => 'Sedang dalam proses investigasi oleh tim.',
        ]);
    }

    #[Test]
    public function admin_can_delete_complaint()
    {
        $complaint = Complaint::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->delete(route('admin.complaints.destroy', $complaint));

        $response->assertRedirect(route('admin.complaints.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('complaints', ['id' => $complaint->id]);
    }

    #[Test]
    public function resolved_status_sets_resolved_at_timestamp()
    {
        $complaint = Complaint::factory()->create([
            'status' => 'investigating',
            'resolved_at' => null,
        ]);

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.complaints.update', $complaint), [
                'status' => 'resolved',
                'admin_notes' => 'Kasus telah diselesaikan.',
            ]);

        $response->assertRedirect(route('admin.complaints.show', $complaint));

        $complaint->refresh();
        $this->assertEquals('resolved', $complaint->status);
        $this->assertNotNull($complaint->resolved_at);
    }

    #[Test]
    public function status_validation_rejects_invalid_status()
    {
        $complaint = Complaint::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.complaints.update', $complaint), [
                'status' => 'invalid_status',
            ]);

        $response->assertSessionHasErrors('status');
    }
}
