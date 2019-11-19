<?php

namespace AdamCrampton\ObjectCache;

use Illuminate\Support\ServiceProvider;

class SimplePackageServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ObjectCache::class, function () {
            return new ObjectCache();
        });

        $this->app->alias(ObjectCache::class, 'object-cache');
    }
}