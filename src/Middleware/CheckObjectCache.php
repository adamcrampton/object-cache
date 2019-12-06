<?php

namespace AdamCrampton\ObjectCache\Middleware;

use Closure;

use AdamCrampton\ObjectCache\ObjectCache;

class CheckObjectCache
{
    protected $objectCache;
    protected $ttl;
    protected $objects;
    protected $redis;

    /**
     * Initialise Middleware Dependencies.
     *
     * @param RedisClusterService $redis
     */
    public function __construct(ObjectCache $redis)
    {
        // Initialise Redis and set ttl values.
        $this->objectCache = $redis;
        $this->ttl = $this->objectCache->ttl;
        $this->redis = $this->objectCache::init();

        /* 
            Add keys of Redis objects you want to check and set in the Middleware here.

            You must specify an array of values for each object:
            cacheKey => Redis Object key
            cacheTtl => either a named TTL from the ObjectCache class, or a numeric value in seconds
            cacheMethod => Middleware method used to repopulate this data in the cache
        */
        $this->objects = [
            [
                // Example:
                // 'cacheKey' => 'exampleKey',
                // 'cacheTtl' => $this->objectCache->ttl['hours']['twentyFour'],
                // 'cacheMethod' => 'setExample'
            ]
        ];
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
        
        return $this->redis->get($object['cacheKey']) ?: 
        $this->redis->pipeline(function($p) use ($object, $ttl) {
            // Set the method to use.
            $methodName = $object['cacheMethod'];

            // Check the item is set - if false, the method failed to return a value.
            if ($this->$methodName($ttl) === false) return false;

            $p->set($object['cacheKey'], $this->$methodName($ttl), 'EX', $object['cacheTtl']);
            $p->get($object['cacheKey']);
        });
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

    /**
     * Example Middleware method.
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
