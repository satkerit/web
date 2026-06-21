<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Report extends Model
{
    use HasFactory, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Laporan';
    }

    protected $fillable = [
        'title',
        'type', // keuangan_publikasi, tata_kelola, tahunan, tahunan_berkelanjutan
        'year',
        'quarter', // nullable for annual reports
        'file_path',
        'file_size',
        'description',
        'is_published',
        'posting_mode', // auto, manual
        'posted_at',
        'scheduled_at',
        'preview_count',
        'download_count'
    ];

    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'file_size' => 'integer',
        'is_published' => 'boolean',
        'posted_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'preview_count' => 'integer',
        'download_count' => 'integer'
    ];

    /**
     * Scope untuk laporan yang sudah dipublikasikan dan waktunya sudah tiba
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('posted_at')
                    ->orWhere('posted_at', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', Carbon::now());
            });
    }

    /**
     * Scope untuk laporan yang dijadwalkan (belum waktunya)
     */
    public function scopeScheduled($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', Carbon::now());
    }

    /**
     * Cek apakah laporan sudah bisa ditampilkan
     */
    public function isVisibleNow(): bool
    {
        if (!$this->is_published) {
            return false;
        }

        if ($this->scheduled_at !== null && $this->scheduled_at > Carbon::now()) {
            return false;
        }

        if ($this->posted_at === null) {
            return true;
        }

        return $this->posted_at <= Carbon::now();
    }

    /**
     * Get status posting
     */
    public function getPostingStatusAttribute(): string
    {
        if (!$this->is_published) {
            return 'draft';
        }

        if ($this->scheduled_at && $this->scheduled_at > Carbon::now()) {
            return 'scheduled';
        }

        if ($this->posted_at && $this->posted_at > Carbon::now()) {
            return 'scheduled';
        }

        return 'published';
    }

    /**
     * Get formatted posted date
     */
    public function getFormattedPostedAtAttribute(): ?string
    {
        return $this->posted_at?->format('d M Y H:i');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    protected static function booted(): void
    {
        $clearCache = function ($model) {
            Cache::forget("report_years_{$model->type}");
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
