<?php

namespace AdamCrampton\ObjectCache\Middleware;

use Closure;

use AdamCrampton\ObjectCache\ObjectCache;

class CheckObjectCache
{
    private $redis;

    /**
     * Initialise Middleware Dependencies
     *
     * @param RedisClusterService $redis
     */
    public function __construct(ObjectCache $redis)
    {
        // Initialise Redis and set ttl values.
        $this->redis = $redis->init();
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
        return $next($request);
    }
}
