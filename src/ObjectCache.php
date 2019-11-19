<?php

namespace AdamCrampton\ObjectCache;

use Config;
use Predis\Client;

class ObjectCache
{
    // Init function made available to controllers.
    public function init()
    {
        // Set Redis connection.
        $parameters = Config::get('object_cache.parameters');

        // Set Redis options.
        $options = Config::get('object_cache.options');
        
        // Initialise Predis.
        return new Client($parameters, $options);
    }
}
