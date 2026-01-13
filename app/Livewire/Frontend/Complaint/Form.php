<?php

namespace App\Livewire\Frontend\Complaint;

use App\Mail\ComplaintMail;
use App\Mail\ComplaintConfirmationMail;
use App\Models\Complaint;
use App\Models\CompanyInfo;
use App\Models\AuditTrail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Form extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $identity_number = '';
    public $type = '';
    public $subject = '';
    public $description = '';
    public $reported_person = '';
    public $reported_department = '';
    public $incident_date = '';
    public $incident_location = '';
    public $attachments = [];
    public $is_anonymous = false;
    public $agree_terms = false;

    public $ticketNumber = null;
    public $submitted = false;

    protected $rules = [
        'type' => 'required|in:fraud,violation,ethics,abuse,safety,other',
        'subject' => 'required|string|max:255',
        'description' => 'required|string|min:50|max:5000',
        'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        'agree_terms' => 'accepted'
    ];

    protected $messages = [
        'type.required' => 'Pilih jenis pelanggaran',
        'subject.required' => 'Subjek laporan wajib diisi',
        'description.required' => 'Deskripsi laporan wajib diisi',
        'description.min' => 'Deskripsi minimal 50 karakter',
        'description.max' => 'Deskripsi maksimal 5000 karakter',
        'agree_terms.accepted' => 'Anda harus menyetujui ketentuan'
    ];

    public function updatedIsAnonymous()
    {
        if ($this->is_anonymous) {
            $this->name = '';
            $this->email = '';
            $this->phone = '';
            $this->identity_number = '';
        }
    }

    public function submit()
    {
        // Add conditional validation for non-anonymous
        $rules = $this->rules;
        if (!$this->is_anonymous) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $this->validate($rules);

        $attachmentPaths = [];
        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $attachmentPaths[] = $file->store('complaints', 'public');
            }
        }

        $complaint = Complaint::create([
            'ticket_number' => Complaint::generateTicketNumber(),
            'name' => $this->is_anonymous ? 'Anonim' : $this->name,
            'email' => $this->is_anonymous ? 'anonymous@whistleblowing.local' : $this->email,
            'phone' => $this->phone,
            'identity_number' => $this->identity_number,
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'reported_person' => $this->reported_person,
            'reported_department' => $this->reported_department,
            'incident_date' => $this->incident_date ?: null,
            'incident_location' => $this->incident_location,
            'attachments' => $attachmentPaths ?: null,
            'is_anonymous' => $this->is_anonymous,
            'status' => 'pending'
        ]);

        AuditTrail::log('create', 'Pengaduan baru diterima: ' . $complaint->ticket_number, $complaint);

        $companyInfo = CompanyInfo::first();

        // Send email notification to admin
        $isWhistleblowing = in_array($this->type, ['fraud', 'violation', 'ethics', 'abuse']);
        $adminEmail = $isWhistleblowing
            ? ($companyInfo->email_whistleblowing ?? $companyInfo->email_complaint ?? $companyInfo->email)
            : ($companyInfo->email_complaint ?? $companyInfo->email);

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ComplaintMail($complaint));
            } catch (\Exception $e) {
                Log::error('Failed to send complaint notification to admin: ' . $e->getMessage());
            }
        }

        // Send confirmation email to reporter (if not anonymous)
        if (!$this->is_anonymous && $this->email) {
            try {
                Mail::to($this->email)->send(new ComplaintConfirmationMail($complaint, $companyInfo));
            } catch (\Exception $e) {
                Log::error('Failed to send complaint confirmation to reporter: ' . $e->getMessage());
            }
        }

        $this->ticketNumber = $complaint->ticket_number;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.frontend.complaint.form');
    }
}
