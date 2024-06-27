<?php

namespace App\Interfaces\Service\Cache;

interface ICacheService
{
    public function storeInCache(string $tag,string $name,int $perPage, int $page,callable $callback,int $expireTime);
    public function invalidateGroupCache(string $tag);
}
