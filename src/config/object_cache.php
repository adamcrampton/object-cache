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
];
