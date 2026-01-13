<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    public static function getGrouped()
    {
        return static::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
    }

    public static function getGroups(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'users' => 'Manajemen Pengguna',
            'roles' => 'Manajemen Role',
            'content' => 'Konten',
            'news' => 'Berita',
            'products' => 'Produk',
            'auctions' => 'Lelang',
            'reports' => 'Laporan',
            'offices' => 'Kantor',
            'careers' => 'Karir',
            'complaints' => 'Pengaduan',
            'settings' => 'Pengaturan',
            'audit' => 'Audit & Log',
        ];
    }
}
