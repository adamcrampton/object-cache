<?php

namespace AdamCrampton\ObjectCache\Test;

use AdamCrampton\ObjectCache\ObjectCacheFacade;
use AdamCrampton\ObjectCache\ObjectCacheServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return AdamCrampton\ObjectCache\ObjectCacheServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [ObjectCacheServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ObjectCache' => ObjectCacheFacade::class,
        ];
    }
}
