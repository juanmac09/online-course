<?php

namespace App\Services\Cache;

use App\Interfaces\Service\Cache\ICacheService;
use Illuminate\Support\Facades\Cache;

class CacheService implements ICacheService
{

    public function storeInCache(string $tag, string $name, int $perPage, int $page, callable $callback, int $expireTime = 10)
    {
        $masterCacheKey = $this->generateMasterCacheKey($tag);
        $cacheKey = $this->generateCacheKey($name, $perPage, $page);

        $cachedKeys = Cache::get($masterCacheKey, []);

        if (!in_array($cacheKey, $cachedKeys)) {
            $cachedKeys[] = $cacheKey;
            Cache::forever($masterCacheKey, $cachedKeys);
        }

        return Cache::remember($cacheKey, now()->addMinutes($expireTime), function () use ($callback) {
            return $callback();
        });
    }



    /**
     * Invalidates the cache group associated with the specified tag.
     *
     * @param string $tag The tag for which the cache group is being invalidated.
     *
     * @return void
     */
    public function invalidateGroupCache(string $tag): void
    {
        $masterCacheKey = $this->generateMasterCacheKey($tag);
        $tagsCache = Cache::get($masterCacheKey);
        if ($tagsCache) {
            foreach ($tagsCache as $cacheKey) {
                Cache::forget($cacheKey);
            }
        }
        Cache::forget($masterCacheKey);
    }

    /**
     * Generate a master cache key for a specific tag.
     *
     * @param string $tag The tag for which the master cache key is being generated.
     *
     * @return string A unique master cache key for the specified tag.
     */
    protected function generateMasterCacheKey(string $tag): string
    {
        return 'master_' . $tag;
    }


    /**
     * Generate a unique cache key for a specific resource.
     *
     * @param string $name The name of the resource.
     * @param int $perPage The number of items per page.
     * @param int $page The current page number.
     *
     * @return string A unique cache key for the specified resource.
     */
    protected function generateCacheKey(string $name, int $perPage, int $page): string
    {
        return $name . '_' . $perPage . '_' . $page; // Generar clave individual
    }
}
