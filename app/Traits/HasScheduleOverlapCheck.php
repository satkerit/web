<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasScheduleOverlapCheck
{
    protected function hasScheduleOverlap(Builder $query, string $dateField, string $startField, string $endField, array $data, ?int $excludeId = null): bool
    {
        $query = $query->clone();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->where($dateField, $data[$dateField]);

        return $query->where(function ($q) use ($startField, $endField, $data) {
            $q->whereBetween($startField, [$data[$startField], $data[$endField]])
                ->orWhereBetween($endField, [$data[$startField], $data[$endField]])
                ->orWhere(function ($inner) use ($startField, $endField, $data) {
                    $inner->where($startField, '<=', $data[$startField])
                        ->where($endField, '>=', $data[$endField]);
                });
        })->exists();
    }
}