<?php

namespace Tests\Unit\Models;

use App\Models\Complaint;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    #[Test]
    public function generate_ticket_number_has_correct_format(): void
    {
        $ticketNumber = Complaint::generateTicketNumber();

        $this->assertMatchesRegularExpression('/^WBS-\d{8}-[A-Z0-9]{6}$/', $ticketNumber);
    }

    #[Test]
    public function generate_ticket_number_contains_current_date(): void
    {
        $ticketNumber = Complaint::generateTicketNumber();
        $expectedDate = now()->format('Ymd');

        $this->assertStringContainsString($expectedDate, $ticketNumber);
    }

    #[Test]
    public function generate_ticket_number_is_unique(): void
    {
        $ticketNumbers = [];
        for ($i = 0; $i < 10; $i++) {
            $ticketNumbers[] = Complaint::generateTicketNumber();
        }

        $uniqueTicketNumbers = array_unique($ticketNumbers);
        $this->assertCount(10, $uniqueTicketNumbers);
    }

    #[Test]
    public function status_label_returns_menunggu_for_pending(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'pending']);

        $this->assertEquals('Menunggu', $complaint->status_label);
    }

    #[Test]
    public function status_label_returns_dalam_review_for_in_review(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'in_review']);

        $this->assertEquals('Dalam Review', $complaint->status_label);
    }

    #[Test]
    public function status_label_returns_investigasi_for_investigating(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'investigating']);

        $this->assertEquals('Investigasi', $complaint->status_label);
    }

    #[Test]
    public function status_label_returns_selesai_for_resolved(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'resolved']);

        $this->assertEquals('Selesai', $complaint->status_label);
    }

    #[Test]
    public function status_label_returns_ditutup_for_closed(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'closed']);

        $this->assertEquals('Ditutup', $complaint->status_label);
    }

    #[Test]
    public function type_label_returns_correct_indonesian_translation(): void
    {
        $types = [
            'fraud' => 'Kecurangan (Fraud)',
            'violation' => 'Pelanggaran Peraturan',
            'ethics' => 'Pelanggaran Etika',
            'abuse' => 'Penyalahgunaan Wewenang',
            'safety' => 'Keselamatan Kerja',
            'other' => 'Lainnya',
        ];

        foreach ($types as $type => $expectedLabel) {
            $complaint = Complaint::factory()->create(['type' => $type]);
            $this->assertEquals($expectedLabel, $complaint->type_label);
        }
    }

    #[Test]
    public function scope_pending_filters_correctly(): void
    {
        Complaint::factory()->count(3)->create(['status' => 'pending']);
        Complaint::factory()->count(2)->create(['status' => 'in_review']);
        Complaint::factory()->count(1)->create(['status' => 'resolved']);

        $pendingComplaints = Complaint::pending()->get();

        $this->assertCount(3, $pendingComplaints);
        $pendingComplaints->each(fn($complaint) => $this->assertEquals('pending', $complaint->status));
    }

    #[Test]
    public function scope_in_progress_filters_correctly(): void
    {
        Complaint::factory()->count(2)->create(['status' => 'pending']);
        Complaint::factory()->count(3)->create(['status' => 'in_review']);
        Complaint::factory()->count(2)->create(['status' => 'investigating']);
        Complaint::factory()->count(1)->create(['status' => 'resolved']);

        $inProgressComplaints = Complaint::inProgress()->get();

        $this->assertCount(5, $inProgressComplaints);
        $inProgressComplaints->each(fn($complaint) => $this->assertContains($complaint->status, ['in_review', 'investigating']));
    }
}
