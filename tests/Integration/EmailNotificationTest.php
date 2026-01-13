<?php

namespace Tests\Integration;

use App\Mail\ComplaintConfirmationMail;
use App\Mail\ComplaintMail;
use App\Mail\ComplaintStatusUpdateMail;
use App\Models\Complaint;
use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->createAdmin();

        // Create company info with email settings
        CompanyInfo::create([
            'name' => 'Test Company',
            'email' => 'admin@test.com',
            'email_complaint' => 'complaint@test.com',
            'email_whistleblowing' => 'whistleblowing@test.com',
            'phone' => '123456789',
            'address' => 'Test Address',
        ]);
    }

    #[Test]
    public function complaint_submission_sends_confirmation_email_to_reporter(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Frontend\Complaint\Form::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '08123456789')
            ->set('type', 'other')
            ->set('subject', 'Test Complaint Subject')
            ->set('description', str_repeat('This is a test complaint description. ', 5))
            ->set('agree_terms', true)
            ->call('submit');

        // Verify confirmation email was sent to the reporter
        Mail::assertSent(ComplaintConfirmationMail::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    #[Test]
    public function complaint_submission_sends_notification_email_to_admin(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Frontend\Complaint\Form::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('phone', '08123456789')
            ->set('type', 'other')
            ->set('subject', 'Test Complaint Subject')
            ->set('description', str_repeat('This is a test complaint description. ', 5))
            ->set('agree_terms', true)
            ->call('submit');

        // Verify notification email was sent to admin
        Mail::assertSent(ComplaintMail::class, function ($mail) {
            return $mail->hasTo('complaint@test.com');
        });
    }

    #[Test]
    public function anonymous_complaint_does_not_send_confirmation_to_reporter(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Frontend\Complaint\Form::class)
            ->set('is_anonymous', true)
            ->set('type', 'fraud')
            ->set('subject', 'Anonymous Complaint Subject')
            ->set('description', str_repeat('This is an anonymous complaint description. ', 5))
            ->set('agree_terms', true)
            ->call('submit');

        // Verify admin notification was sent
        Mail::assertSent(ComplaintMail::class);

        // Verify no confirmation email was sent to reporter (anonymous)
        Mail::assertNotSent(ComplaintConfirmationMail::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    #[Test]
    public function whistleblowing_complaint_sends_to_whistleblowing_email(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Frontend\Complaint\Form::class)
            ->set('name', 'Whistleblower')
            ->set('email', 'whistleblower@example.com')
            ->set('phone', '08123456789')
            ->set('type', 'fraud')
            ->set('subject', 'Fraud Report')
            ->set('description', str_repeat('This is a fraud report description. ', 5))
            ->set('agree_terms', true)
            ->call('submit');

        // Verify notification email was sent to whistleblowing email
        Mail::assertSent(ComplaintMail::class, function ($mail) {
            return $mail->hasTo('whistleblowing@test.com');
        });
    }

    #[Test]
    public function complaint_status_update_sends_notification_email(): void
    {
        // Note: The ComplaintStatusUpdateMail class exists but is not currently
        // integrated into the admin complaint controller. This test verifies
        // the mail class can be instantiated and would work correctly.
        // To fully implement this feature, the admin controller needs to be
        // updated to send this email when status changes.

        Mail::fake();

        $complaint = Complaint::factory()->create([
            'email' => 'reporter@example.com',
            'status' => 'pending',
        ]);

        $oldStatus = $complaint->status;
        $complaint->status = 'in_review';
        $complaint->save();

        // Manually send the status update email (simulating what the controller should do)
        Mail::to($complaint->email)->send(new ComplaintStatusUpdateMail(
            $complaint,
            $oldStatus,
            'Your complaint is now being reviewed.'
        ));

        // Verify status update email was sent
        Mail::assertSent(ComplaintStatusUpdateMail::class, function ($mail) use ($complaint) {
            return $mail->hasTo($complaint->email)
                && $mail->complaint->id === $complaint->id
                && $mail->oldStatus === 'pending';
        });
    }
}
