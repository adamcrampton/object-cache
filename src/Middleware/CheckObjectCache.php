<?php

namespace AdamCrampton\ObjectCache\Middleware;

use Closure;

use AdamCrampton\ObjectCache\ObjectCache;

class CheckObjectCache
{
    private $ttl;
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
        $this->ttl = [ 
            // Cache store type.
            'apc' => [
                'default' => 86400
            ],
            'array' => [
                'default' => 86400
            ],
            'database' => [
                'default' => 86400
            ],
            'file' => [
                'default' => 86400
            ],
            'memcached' => [
                'default' => 86400
            ],
            'redis' => [
                'default' => 86400
            ],
            // Handy values.
            'minutes' => [
                'one' => 60,
                'five' => 300,
                'ten' => 600,
                'fifteen' => 900,
                'twenty' => 1200,
                'thirty' => 1800,
                'forty' => 2400,
                'fortyFive' => 2700,
                'fifty' => 3000,
                'sixty' => 3600
            ],
            'hours' => [
                'one' => 3600,
                'two' => 7200,
                'three' => 10800,
                'four' => 14400,
                'five' => 18000,
                'six' => 21600,
                'seven' => 25200,
                'eight' => 28800,
                'nine' => 32400,
                'ten' => 36000,
                'eleven' => 39600,
                'twelve' => 43200,
                'twentyFour' => 86400
            ],
            'days' => [
                'one' => 86400,
                'two' => 172800,
                'three' => 259200,
                'four' => 345600,
                'five' => 432000,
                'six' => 518400,
                'seven' => 604800,
                'fourteen' => 1209600,
                'twentyEight' => 2419200
            ],
            'weeks' => [
                'one' => 604800,
                'two' => 1209600,
                'three' => 1814400,
                'four' => 2419200,
                'five' => 3024000,
                'six' => 3628800
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
        return $next($request);
    }
}
