<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HandlesFilterableQuery
{
    protected function applyFilters(Builder $query, array $filterConfig, array $requestData): Builder
    {
        foreach ($filterConfig as $field => $config) {
            $operator = $config['operator'] ?? 'exact';
            $column = $config['column'] ?? $field;

            if (!isset($requestData[$field]) || blank($requestData[$field])) {
                continue;
            }

            $value = $requestData[$field];

            switch ($operator) {
                case 'like':
                    $query->where($column, 'like', "%{$value}%");
                    break;
                case 'or_like':
                    $query->orWhere($column, 'like', "%{$value}%");
                    break;
                case 'search':
                    $search = $value;
                    $query->where(function ($q) use ($config, $search) {
                        foreach ($config['columns'] as $col) {
                            $q->orWhere($col, 'like', "%{$search}%");
                        }
                    });
                    break;
                case 'date_from':
                    $query->whereDate($column, '>=', $value);
                    break;
                case 'date_to':
                    $query->whereDate($column, '<=', $value);
                    break;
                case 'boolean':
                    $query->where($column, $value === 'active' || $value === true);
                    break;
                default:
                    $query->where($column, $value);
                    break;
            }
        }

        return $query;
    }
}