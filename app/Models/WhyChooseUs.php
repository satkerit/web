<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    use HasFactory;

    protected $table = 'why_choose_us';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'color_theme',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    /**
     * Get the background class based on color theme.
     */
    public function getBgClassAttribute()
    {
        $theme = $this->color_theme ?? 'primary';
        return "bg-{$theme}-100";
    }

    /**
     * Get the text color class based on color theme.
     */
    public function getTextClassAttribute()
    {
        $theme = $this->color_theme ?? 'primary';
        return "text-{$theme}-600";
    }

    /**
     * Get the hover background class based on color theme.
     */
    public function getHoverBgClassAttribute()
    {
        $theme = $this->color_theme ?? 'primary';
        return "group-hover:bg-{$theme}-500";
    }

    /**
     * Get available themes for selection.
     */
    public static function getThemes()
    {
        return [
            'primary' => 'Primary (Brand)',
            'emerald' => 'Emerald (Green)',
            'blue' => 'Blue',
            'amber' => 'Amber (Orange)',
            'rose' => 'Rose (Red)',
            'purple' => 'Purple',
            'teal' => 'Teal',
            'cyan' => 'Cyan',
            'indigo' => 'Indigo',
        ];
    }
}
