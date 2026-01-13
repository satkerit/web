<?php

namespace App\Livewire\Frontend\Newsletter;

use Livewire\Component;

class Subscribe extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email|max:255',
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function subscribe()
    {
        $this->validate();

        // Here you can save to database or integrate with email service
        // For example: Mailchimp, SendGrid, etc.

        // Example: Save to database
        // Newsletter::firstOrCreate(['email' => $this->email]);

        session()->flash('success', 'Terima kasih! Anda telah berlangganan newsletter kami.');

        // Reset form
        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.frontend.newsletter.subscribe');
    }
}
