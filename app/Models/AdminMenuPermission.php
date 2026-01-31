<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminMenuPermission extends Model
{
    protected $fillable = [
        'admin_menu_id',
        'role_id',
        'can_access',
    ];

    protected $casts = [
        'can_access' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(AdminMenu::class, 'admin_menu_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
