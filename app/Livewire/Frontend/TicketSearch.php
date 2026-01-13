<?php

namespace App\Livewire\Frontend;

use App\Models\Complaint;
use App\Models\CustomerComplaint;
use Livewire\Component;

class TicketSearch extends Component
{
    public string $ticketNumber = '';
    public string $type = 'customer'; // 'customer' or 'whistleblowing'
    public ?array $result = null;
    public bool $searched = false;
    public ?string $error = null;

    public function mount(string $type = 'customer')
    {
        $this->type = $type;
    }

    public function search()
    {
        $this->error = null;
        $this->result = null;
        $this->searched = true;

        if (empty(trim($this->ticketNumber))) {
            $this->error = 'Masukkan nomor tiket untuk melacak pengaduan Anda.';
            return;
        }

        $ticketNumber = strtoupper(trim($this->ticketNumber));

        if ($this->type === 'whistleblowing') {
            $complaint = Complaint::where('ticket_number', $ticketNumber)->first();

            if ($complaint) {
                $this->result = [
                    'ticket_number' => $complaint->ticket_number,
                    'subject' => $complaint->subject,
                    'status' => $complaint->status,
                    'status_label' => $complaint->status_label,
                    'type' => $complaint->type_label,
                    'created_at' => $complaint->created_at->format('d M Y, H:i'),
                    'resolved_at' => $complaint->resolved_at?->format('d M Y, H:i'),
                ];
            }
        } else {
            $complaint = CustomerComplaint::where('ticket_number', $ticketNumber)->first();

            if ($complaint) {
                $this->result = [
                    'ticket_number' => $complaint->ticket_number,
                    'subject' => $complaint->subject,
                    'status' => $complaint->status,
                    'status_label' => $complaint->status_label,
                    'category' => $complaint->category_label,
                    'priority' => $complaint->priority_label,
                    'created_at' => $complaint->created_at->format('d M Y, H:i'),
                    'resolved_at' => $complaint->resolved_at?->format('d M Y, H:i'),
                    'resolution' => $complaint->resolution,
                ];
            }
        }

        if (!$this->result) {
            $this->error = 'Tiket tidak ditemukan. Pastikan nomor tiket yang Anda masukkan benar.';
        }
    }

    public function resetSearch()
    {
        $this->ticketNumber = '';
        $this->result = null;
        $this->searched = false;
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.frontend.ticket-search');
    }
}
