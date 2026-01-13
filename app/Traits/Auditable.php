<?php

namespace App\Traits;

use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        // Log saat data dibuat
        static::created(function (Model $model) {
            if (Auth::check()) {
                AuditTrail::log(
                    'create',
                    static::getAuditDescription('create', $model),
                    $model,
                    null,
                    $model->getAttributes()
                );
            }
        });

        // Log saat data diupdate
        static::updated(function (Model $model) {
            if (Auth::check()) {
                $oldValues = array_intersect_key(
                    $model->getOriginal(),
                    $model->getChanges()
                );
                $newValues = $model->getChanges();

                // Hapus timestamp dari perubahan
                unset($oldValues['updated_at'], $newValues['updated_at']);

                if (!empty($newValues)) {
                    AuditTrail::log(
                        'update',
                        static::getAuditDescription('update', $model),
                        $model,
                        $oldValues,
                        $newValues
                    );
                }
            }
        });

        // Log saat data dihapus
        static::deleted(function (Model $model) {
            if (Auth::check()) {
                AuditTrail::log(
                    'delete',
                    static::getAuditDescription('delete', $model),
                    $model,
                    $model->getAttributes(),
                    null
                );
            }
        });
    }

    protected static function getAuditDescription(string $action, Model $model): string
    {
        $modelName = static::getAuditModelName();
        $identifier = static::getAuditIdentifier($model);

        return match ($action) {
            'create' => "Membuat {$modelName}: {$identifier}",
            'update' => "Mengubah {$modelName}: {$identifier}",
            'delete' => "Menghapus {$modelName}: {$identifier}",
            default => "{$action} {$modelName}: {$identifier}"
        };
    }

    protected static function getAuditModelName(): string
    {
        // Override di model untuk nama yang lebih readable
        return class_basename(static::class);
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        // Coba ambil identifier yang readable
        return $model->title
            ?? $model->name
            ?? $model->ticket_number
            ?? $model->slug
            ?? "ID: {$model->id}";
    }
}
