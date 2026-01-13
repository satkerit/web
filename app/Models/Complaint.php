<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaduan';
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
        'identity_number',
        'type',
        'subject',
        'description',
        'reported_person',
        'reported_department',
        'incident_date',
        'incident_location',
        'attachments',
        'is_anonymous',
        'status',
        'admin_notes',
        'resolved_at'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_anonymous' => 'boolean',
        'incident_date' => 'date',
        'resolved_at' => 'datetime'
    ];

    public static function generateTicketNumber(): string
    {
        $prefix = 'WBS';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "{$prefix}-{$date}-{$random}";
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['in_review', 'investigating']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_review' => 'Dalam Review',
            'investigating' => 'Investigasi',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => $this->status
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'fraud' => 'Kecurangan (Fraud)',
            'violation' => 'Pelanggaran Peraturan',
            'ethics' => 'Pelanggaran Etika',
            'abuse' => 'Penyalahgunaan Wewenang',
            'safety' => 'Keselamatan Kerja',
            'other' => 'Lainnya',
            default => $this->type
        };
    }
}
