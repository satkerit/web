<?php

namespace App\Mail;

use App\Models\CustomerComplaint;
use App\Models\CompanyInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerComplaintConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomerComplaint $complaint,
        public ?CompanyInfo $companyInfo
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pengaduan - ' . $this->complaint->ticket_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-complaint-confirmation',
        );
    }
}
