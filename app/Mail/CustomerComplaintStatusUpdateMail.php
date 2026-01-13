<?php

namespace App\Mail;

use App\Models\CustomerComplaint;
use App\Models\CompanyInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerComplaintStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomerComplaint $complaint,
        public string $oldStatus,
        public ?string $adminNotes = null,
        public ?string $resolution = null,
        public ?CompanyInfo $companyInfo = null
    ) {
        $this->companyInfo = $companyInfo ?? CompanyInfo::first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Pengaduan Nasabah - ' . $this->complaint->ticket_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-complaint-status-update',
        );
    }
}
