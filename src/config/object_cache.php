<?php

/*
|--------------------------------------------------------------------------
| Redis Config
|--------------------------------------------------------------------------
|
| Config values for use with Predis library.
|
*/
return [
    'parameters' => [
        'tcp://' . env('REDIS_HOST')
    ],
    'options' => [
        'cluster' => 'redis'
    ]
];
