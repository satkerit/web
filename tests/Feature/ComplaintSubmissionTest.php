<?php

namespace Tests\Feature;

use App\Livewire\Frontend\Complaint\Form;
use App\Models\Complaint;
use App\Models\CompanyInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ComplaintSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create company info for email notifications
        CompanyInfo::create([
            'name' => 'Test Company',
            'email' => 'admin@test.com',
            'address' => 'Test Address',
            'phone' => '08123456789',
        ]);

        Mail::fake();
    }

    #[Test]
    public function visitor_can_submit_complaint_with_valid_data()
    {
        Livewire::test(Form::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '08123456789')
            ->set('type', 'fraud')
            ->set('subject', 'Test Complaint Subject')
            ->set('description', str_repeat('This is a test complaint description. ', 5))
            ->set('agree_terms', true)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('ticketNumber', fn($value) => str_starts_with($value, 'WBS-'));

        $this->assertDatabaseHas('complaints', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'type' => 'fraud',
            'subject' => 'Test Complaint Subject',
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function validation_errors_for_invalid_data()
    {
        Livewire::test(Form::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('type', '')
            ->set('subject', '')
            ->set('description', 'Too short')
            ->set('agree_terms', false)
            ->call('submit')
            ->assertHasErrors(['type', 'subject', 'description', 'agree_terms']);

        $this->assertDatabaseCount('complaints', 0);
    }

    #[Test]
    public function anonymous_complaint_submission()
    {
        Livewire::test(Form::class)
            ->set('is_anonymous', true)
            ->set('type', 'violation')
            ->set('subject', 'Anonymous Complaint')
            ->set('description', str_repeat('This is an anonymous complaint description. ', 5))
            ->set('agree_terms', true)
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('complaints', [
            'name' => 'Anonim',
            'email' => 'anonymous@whistleblowing.local',
            'type' => 'violation',
            'subject' => 'Anonymous Complaint',
            'is_anonymous' => true,
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function ticket_number_is_generated()
    {
        Livewire::test(Form::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('type', 'ethics')
            ->set('subject', 'Ethics Violation Report')
            ->set('description', str_repeat('This is a detailed ethics violation report. ', 5))
            ->set('agree_terms', true)
            ->call('submit')
            ->assertSet('submitted', true);

        $complaint = Complaint::first();
        $this->assertNotNull($complaint->ticket_number);
        $this->assertMatchesRegularExpression('/^WBS-\d{8}-[A-Z0-9]{6}$/', $complaint->ticket_number);
    }

    #[Test]
    public function initial_status_is_pending()
    {
        Livewire::test(Form::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('type', 'safety')
            ->set('subject', 'Safety Concern')
            ->set('description', str_repeat('This is a safety concern that needs attention. ', 5))
            ->set('agree_terms', true)
            ->call('submit')
            ->assertSet('submitted', true);

        $complaint = Complaint::first();
        $this->assertEquals('pending', $complaint->status);
    }

    #[Test]
    public function non_anonymous_requires_name_and_email()
    {
        Livewire::test(Form::class)
            ->set('is_anonymous', false)
            ->set('name', '')
            ->set('email', '')
            ->set('type', 'fraud')
            ->set('subject', 'Test Subject')
            ->set('description', str_repeat('This is a test description for validation. ', 5))
            ->set('agree_terms', true)
            ->call('submit')
            ->assertHasErrors(['name', 'email']);

        $this->assertDatabaseCount('complaints', 0);
    }
}
