<?php

namespace AdamCrampton\ObjectCache\Middleware;

use Closure;
use Config;

use AdamCrampton\ObjectCache\ObjectCache;

class CheckObjectCache
{
    protected $objectCache;
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
        $this->redis = $this->objectCache->init();
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
     * Add keys of Redis objects you want to check and set in the Middleware here.
     * You must specify an array of values for each object:
     * cacheKey => Redis Object key
     * cacheTtl => either a named TTL from the ObjectCache class, or a numeric value in seconds
     * cacheMethod => Middleware method used to repopulate this data in the cache
     * 
     * @return void
     */
    public function handleObjects()
    {
        $objects = [
            // Example:
            // 'cacheKey' => 'exampleKey',
            // 'cacheTtl' => $this->objectCache->ttl['hours']['twentyFour']
            // 'cacheMethod' => 'setExample'
        ];

        foreach ($objects as $objectValues) {
            // Ensure both items are received.
            if (!array_key_exists('cacheKey', $objectValues) || (!array_key_exists('cacheTtl', $objectValues))) continue;

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
        return $this->redis->get($object['cacheKey']) ?: 
            $this->redis->pipeline(function($p) use ($object) {
                $methodName = $object['cacheMethod'];
                $p->set($object['cacheKey'], $this->$methodName($object['cacheTtl']), 'EX', $object['cacheTtl']);
                $p->get($object['cacheKey']);
            });
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
