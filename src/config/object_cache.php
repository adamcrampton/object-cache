<?php

/*
|--------------------------------------------------------------------------
| Object Cache Config
|--------------------------------------------------------------------------
|
| Config values for use with Object Cache library.
|
*/
return [
    /*
        // Predis configuration.
    */
    'parameters' => [
        'tcp://' . env('REDIS_HOST')
    ],
    'options' => [
        'cluster' => 'redis'
    ],
    /*
        Set the class with namespace where the cache generation methods are stored.
    */
    'methodClass' => 'AdamCrampton\ObjectCache\ObjectCache\CacheMethodStore',
    /*
        Fallback settings - when Redis is unavailable.
    */
    'fallback' => [
        'enabled' => true,
        // Pass the request object into the constructor of your cache store class.
        'passRequest' => false
    ],
    /*
        Log errors to debug.
    */
    'logErrors' => false
];
