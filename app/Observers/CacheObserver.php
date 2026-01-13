<?php

namespace App\Observers;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;

class CacheObserver
{
    protected array $cacheKeys;

    public function __construct(array $cacheKeys = [])
    {
        $this->cacheKeys = $cacheKeys;
    }

    public function saved(Model $model): void
    {
        $this->clearCache();
    }

    public function deleted(Model $model): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        foreach ($this->cacheKeys as $key) {
            CacheService::clear($key);
        }
    }
}
