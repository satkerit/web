<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ComplaintSetting extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaturan Pengaduan Nasabah';
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        return 'Complaint Settings';
    }

    protected $fillable = [
        'admin_email',
        'cc_emails',
        'notify_on_new',
        'notify_on_status_change',
        'send_confirmation_to_customer',
        'sla_days_low',
        'sla_days_medium',
        'sla_days_high',
        'require_account_number',
        'require_phone',
        'allow_attachments',
        'max_attachments',
        'max_file_size_mb',
        'allowed_file_types',
        'ticket_prefix',
        'auto_assign_priority',
        'form_intro_text',
        'success_message',
        'terms_text',
        'active_categories',
    ];

    protected $casts = [
        'notify_on_new'                 => 'boolean',
        'notify_on_status_change'       => 'boolean',
        'send_confirmation_to_customer' => 'boolean',
        'require_account_number'        => 'boolean',
        'require_phone'                 => 'boolean',
        'allow_attachments'             => 'boolean',
        'auto_assign_priority'          => 'boolean',
        'sla_days_low'                  => 'integer',
        'sla_days_medium'               => 'integer',
        'sla_days_high'                 => 'integer',
        'max_attachments'               => 'integer',
        'max_file_size_mb'              => 'integer',
        'active_categories'             => 'array',
    ];

    /**
     * Semua kategori yang tersedia
     */
    public static function availableCategories(): array
    {
        return [
            'service'     => 'Pelayanan',
            'product'     => 'Produk',
            'transaction' => 'Transaksi',
            'facility'    => 'Fasilitas',
            'staff'       => 'Petugas/Karyawan',
            'other'       => 'Lainnya',
        ];
    }

    /**
     * Ambil pengaturan dari cache, buat default jika belum ada
     */
    public static function getSettings(): self
    {
        return Cache::remember('complaint_settings', 3600, function () {
            return self::first() ?? self::create([
                'notify_on_new'                 => true,
                'notify_on_status_change'       => true,
                'send_confirmation_to_customer' => true,
                'sla_days_low'                  => 14,
                'sla_days_medium'               => 7,
                'sla_days_high'                 => 3,
                'require_account_number'        => false,
                'require_phone'                 => true,
                'allow_attachments'             => true,
                'max_attachments'               => 5,
                'max_file_size_mb'              => 5,
                'allowed_file_types'            => 'pdf,doc,docx,jpg,jpeg,png',
                'ticket_prefix'                 => 'ADU',
                'auto_assign_priority'          => true,
                'active_categories'             => array_keys(self::availableCategories()),
            ]);
        });
    }

    /**
     * Hapus cache
     */
    public static function clearCache(): void
    {
        Cache::forget('complaint_settings');
    }

    /**
     * Ambil daftar CC email sebagai array
     */
    public function getCcEmailsArray(): array
    {
        if (empty($this->cc_emails)) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->cc_emails)));
    }

    /**
     * Ambil tipe file yang diizinkan sebagai array
     */
    public function getAllowedFileTypesArray(): array
    {
        return array_filter(array_map('trim', explode(',', $this->allowed_file_types ?? '')));
    }

    /**
     * Cek apakah kategori aktif
     */
    public function isCategoryActive(string $category): bool
    {
        $active = $this->active_categories ?? array_keys(self::availableCategories());
        return in_array($category, $active);
    }

    /**
     * Ambil label SLA berdasarkan prioritas
     */
    public function getSlaDays(string $priority): int
    {
        return match ($priority) {
            'high'   => $this->sla_days_high,
            'low'    => $this->sla_days_low,
            default  => $this->sla_days_medium,
        };
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }
}
