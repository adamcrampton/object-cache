<?php

namespace AdamCrampton\ObjectCache;

use Config;
use Predis\Client;

class ObjectCache
{
    public $ttl;

    public function __construct()
    {
        $this->ttl = [ 
            // Cache store type.
            'default' => [
                'default' => 86400
            ],
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
     * Init function made available to controllers.
     *
     * @return void
     */
    public static function init()
    {
        // Set Redis connection.
        $parameters = Config::get('object_cache.parameters');

        // Set Redis options.
        $options = Config::get('object_cache.options');
        
        // Initialise Predis.
        return new Client($parameters, $options);
    }

    /**
     * Returns data in useable format.
     * If decode is not false, and not set to 'array', an object will be returned.
     *
     * @param bool $decode
     * @return array
     */
    public static function get($cacheKey, $decode = false)
    {   
        $redis = self::init();
        $data = $redis->get($cacheKey);

        return $decode ? json_decode($data, $decode === 'array') : $data;
    }
}
