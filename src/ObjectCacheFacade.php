<?php

namespace AdamCrampton\ObjectCache;

use Illuminate\Support\Facades\Facade;

class ObjectCacheFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'object-cache';
    }
}