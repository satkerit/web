<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'section_image',
        'badge_text',
        'badge_icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function setSectionTitleAttribute($value)
    {
        $this->attributes['section_title'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setSectionSubtitleAttribute($value)
    {
        $this->attributes['section_subtitle'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    /**
     * Get the singleton instance (only one settings record)
     */
    public static function getSettings()
    {
        return static::unguarded(function () {
            return self::firstOrCreate(
                ['id' => 1],
                [
                    'section_title' => 'Mengapa Memilih Kami',
                    'section_subtitle' => 'Kami memberikan layanan terbaik dengan standar syariah yang terpercaya',
                    'badge_text' => '100% Syariah Compliant',
                    'is_active' => true,
                ]
            );
        });
    }
}
