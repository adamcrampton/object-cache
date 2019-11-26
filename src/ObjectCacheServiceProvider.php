<?php

namespace AdamCrampton\ObjectCache;

use Illuminate\Support\ServiceProvider;

use AdamCrampton\ObjectCache\ObjectCache;

class ObjectCacheServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->config->get('object_cache') === null) {
            $this->app->config->set('object_cache', require __DIR__ . '/config/object_cache.php');
        }

        $this->app->singleton(ObjectCache::class, function () {
            return new ObjectCache();
        });

        $this->app->alias(ObjectCache::class, 'object-cache');

        $this->app['router']->middleware('object', 'AdamCrampton\ObjectCache\CheckObjectCache');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/object_cache.php' => config_path('object_cache.php'),
        ]);
    }
}
