<?php

namespace AdamCrampton\ObjectCache\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use AdamCrampton\ObjectCache\ObjectCache;

class CheckObjectCache
{
    protected $methodClass;
    protected $methodStore;
    protected $objectCache;
    protected $objects;
    protected $redis;
    protected $ttl;

    /**
     * Initialise Middleware Dependencies + Settings.
     * 
     * @param AdamCrampton\ObjectCache\ObjectCache $redis
     * @param Illuminate\Http\Request $request
     */
    public function __construct(ObjectCache $redis, Request $request)
    {
        // Initialise Cache.
        $this->objectCache = $redis;
        $this->redis = $this->objectCache::init();
        $this->ttl = $this->objectCache->ttl;

        // Configure methods and objects.
        $this->methodClass = Config::get('object_cache.methodClass');
        $this->methodStore = new $this->methodClass($request);
        $this->objects = $this->methodStore->objects;
    }
    
    /**
     * This middleware is responsible for checking for 
     * certain cache values, and repopulating the data if expired.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->handleObjects();

        return $next($request);
    }

    /**
     * Ensure all values are received, then check and set in Redis.
     * 
     * @return void
     */
    public function handleObjects()
    {
        foreach ($this->objects as $objectValues) {
            // Ensure both items are received.
            if (!array_key_exists('cacheKey', $objectValues) || (!array_key_exists('cacheTtl', $objectValues)) || (!array_key_exists('cacheMethod', $objectValues))) continue;

            // Hand off to check and set methods.
            $this->checkObject($objectValues);
        }
    }

    /**
     * Check for object values and return.
     * If not found, set first, then return.
     *
     * @return mixed
     */
    protected function checkObject(array $object)
    {
        // Check TTL is valid.
        $ttl = $this->checkTtl($object['cacheTtl']);

        // Get the method store class instance.
        $methodStore = $this->methodStore;
        
        // Get data, set in cache if not found.
        try {
            $data = $this->redis->get($object['cacheKey']) ?? 
                $this->redis->pipeline(function($p) use ($object, $ttl, $methodStore) {
                    // Set the method to use.
                    $methodName = $object['cacheMethod'];
    
                    // Fetch data + set in cache.
                    $p->set($object['cacheKey'], $methodStore->$methodName($ttl), 'EX', $object['cacheTtl']);
                });
        } catch (\Exception $e) {
            Log::debug('Could not get data from Redis for cache key ' . $object['cacheKey']);
        }
    }
    
    /**
     * Ensure a valid TTL is used.
     *
     * @param string $ttl
     * @return int
     */
    protected function checkTtl($ttl)
    {
        return is_numeric($ttl) ? intval($ttl) : $this->redis->ttl['default']['default'];
    }
}
