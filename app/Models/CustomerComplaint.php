<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerComplaint extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaduan Nasabah';
    }

    protected static function getAuditIdentifier(\Illuminate\Database\Eloquent\Model $model): string
    {
        return $model->ticket_number ?? "ID: {$model->id}";
    }

    protected $fillable = [
        'ticket_number',
        'name',
        'email',
        'phone',
        'account_number',
        'category',
        'subcategory',
        'subject',
        'description',
        'branch_office',
        'incident_date',
        'attachments',
        'priority',
        'status',
        'resolution',
        'admin_notes',
        'handled_by',
        'resolved_at'
    ];

    protected $casts = [
        'attachments' => 'array',
        'incident_date' => 'date',
        'resolved_at' => 'datetime'
    ];

    public static function generateTicketNumber(): string
    {
        $prefix = 'ADU';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "{$prefix}-{$date}-{$random}";
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => $this->status
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'service' => 'Pelayanan',
            'product' => 'Produk',
            'transaction' => 'Transaksi',
            'facility' => 'Fasilitas',
            'staff' => 'Petugas/Karyawan',
            'other' => 'Lainnya',
            default => $this->category
        };
    }

    public function getSubcategoryLabelAttribute(): ?string
    {
        if (!$this->subcategory) {
            return null;
        }

        return match ($this->subcategory) {
            'tabungan' => 'Tabungan',
            'pembiayaan' => 'Pembiayaan',
            default => ucfirst($this->subcategory)
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            default => $this->priority
        };
    }
}
