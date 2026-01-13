<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Complaint $complaint
    ) {}

    public function envelope(): Envelope
    {
        $prefix = $this->complaint->type === 'fraud' || $this->complaint->type === 'violation'
            ? '[Whistleblowing]'
            : '[Pengaduan]';

        return new Envelope(
            subject: $prefix . ' ' . $this->complaint->ticket_number . ' - ' . $this->complaint->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint',
        );
    }
}
