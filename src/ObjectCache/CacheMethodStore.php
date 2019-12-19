<?php

namespace AdamCrampton\ObjectCache\ObjectCache;

use Illuminate\Support\Facades\Config;

class CacheMethodStore
{
    public function __construct()
    {
        /* 
            Add keys of Redis objects you want to check and set in the Middleware here.

            You must specify an array of values for each object:
            cacheKey => Redis Object key
            cacheTtl => either a named TTL from the ObjectCache class, or a numeric value in seconds
            cacheMethod => Middleware method used to repopulate this data in the cache
        */
        $objects = [
            [
                'cacheKey' => 'example',
                'cacheTtl' => $this->objectCache->ttl['weeks']['four'],
                'cacheMethod' => 'setExample'
            ]
        ];
    }

    /**
     * Example method.
     *
     * @return array
     */
    protected function setExample()
    {
        return json_encode([
            'data' => 'Some data'
        ]);
    }
}
