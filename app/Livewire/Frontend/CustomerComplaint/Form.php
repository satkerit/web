<?php

namespace App\Livewire\Frontend\CustomerComplaint;

use App\Mail\CustomerComplaintMail;
use App\Mail\CustomerComplaintConfirmationMail;
use App\Models\CustomerComplaint;
use App\Models\CompanyInfo;
use App\Models\Office;
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
    public $account_number = '';
    public $category = '';
    public $subcategory = '';
    public $subject = '';
    public $description = '';
    public $branch_office = '';
    public $incident_date = '';
    public $attachments = [];
    public $agree_terms = false;

    public $ticketNumber = null;
    public $submitted = false;
    public $offices = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'category' => 'required|in:service,product,transaction,facility,staff,other',
        'subcategory' => 'required_if:category,product|nullable|in:tabungan,pembiayaan',
        'subject' => 'required|string|max:255',
        'description' => 'required|string|min:20|max:3000',
        'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        'agree_terms' => 'accepted'
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'phone.required' => 'Nomor telepon wajib diisi',
        'category.required' => 'Pilih kategori pengaduan',
        'subcategory.required_if' => 'Pilih sub kategori produk',
        'subject.required' => 'Subjek pengaduan wajib diisi',
        'description.required' => 'Deskripsi pengaduan wajib diisi',
        'description.min' => 'Deskripsi minimal 20 karakter',
        'description.max' => 'Deskripsi maksimal 3000 karakter',
        'agree_terms.accepted' => 'Anda harus menyetujui ketentuan'
    ];

    public function mount()
    {
        $this->offices = Office::where('is_active', true)->orderBy('name')->get(['id', 'name', 'type']);
    }

    public function submit()
    {
        $this->validate();

        $attachmentPaths = [];
        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $attachmentPaths[] = $file->store('customer-complaints', 'public');
            }
        }

        $complaint = CustomerComplaint::create([
            'ticket_number' => CustomerComplaint::generateTicketNumber(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'account_number' => $this->account_number ?: null,
            'category' => $this->category,
            'subcategory' => $this->category === 'product' ? $this->subcategory : null,
            'subject' => $this->subject,
            'description' => $this->description,
            'branch_office' => $this->branch_office ?: null,
            'incident_date' => $this->incident_date ?: null,
            'attachments' => $attachmentPaths ?: null,
            'priority' => 'medium',
            'status' => 'pending'
        ]);

        AuditTrail::log('create', 'Pengaduan nasabah baru diterima: ' . $complaint->ticket_number, $complaint);

        $companyInfo = CompanyInfo::first();
        $adminEmail = $companyInfo->email_complaint ?? $companyInfo->email;

        // Send email notification to admin
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new CustomerComplaintMail($complaint));
            } catch (\Exception $e) {
                Log::error('Failed to send customer complaint notification: ' . $e->getMessage());
            }
        }

        // Send confirmation email to customer
        if ($this->email) {
            try {
                Mail::to($this->email)->send(new CustomerComplaintConfirmationMail($complaint, $companyInfo));
            } catch (\Exception $e) {
                Log::error('Failed to send customer complaint confirmation: ' . $e->getMessage());
            }
        }

        $this->ticketNumber = $complaint->ticket_number;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.frontend.customer-complaint.form');
    }
}
