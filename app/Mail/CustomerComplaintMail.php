<?php

namespace App\Mail;

use App\Models\CustomerComplaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CustomerComplaint $complaint) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Pengaduan Nasabah] ' . $this->complaint->ticket_number . ' - ' . $this->complaint->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-complaint',
        );
    }
}
