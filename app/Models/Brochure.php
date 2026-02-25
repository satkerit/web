<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Brochure extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getDownloadUrlAttribute()
    {
        return route('brochures.download', $this);
    }

    protected static function getAuditModelName(): string
    {
        return 'Brosur';
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        return $model->original_name;
    }
}
