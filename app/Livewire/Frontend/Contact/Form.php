<?php

namespace App\Livewire\Frontend\Contact;

use App\Mail\ContactFormMail;
use App\Models\CompanyInfo;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Form extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'subject.required' => 'Subjek wajib diisi.',
        'message.required' => 'Pesan wajib diisi.',
        'message.min' => 'Pesan minimal 10 karakter.',
    ];

    public function submit()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ];

        // Get email from company info
        $companyInfo = CompanyInfo::first();
        $toEmail = $companyInfo->email_contact ?? $companyInfo->email ?? config('mail.from.address');

        if ($toEmail) {
            try {
                Mail::to($toEmail)->send(new ContactFormMail($data));
            } catch (\Exception $e) {
                // Log error but don't fail the submission
                Log::error('Failed to send contact email: ' . $e->getMessage());
            }
        }

        session()->flash('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');

        // Reset form
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.frontend.contact.form');
    }
}
