# object-cache
This Laravel package will allow you to set and forget objects in your Redis cache, with automated Middleware and convenient pre-bundled TTL settings. All you need to supply is a TTL, key name, and method for regenerating the value.

## Installation
* Run ```composer require adamcrampton/object-cache``` in your project directory
* Add library service provider ```AdamCrampton\ObjectCache\ObjectCacheServiceProvider::class``` and facade ```'ObjectCache' => AdamCrampton\ObjectCache\ObjectCacheFacade::class``` to ```config/app.php```
* Create a class for storing your methods, adding use statements for ```AdamCrampton\ObjectCache\ObjectCache``` and ```AdamCrampton\ObjectCache\Middleware\CheckObjectCache```
* Add the package Middleware ```\AdamCrampton\ObjectCache\Middleware\CheckObjectCache::class``` to ```app/Http/Kernel.php```
* Configure your routes to use middleware - see https://laravel.com/docs/5.8/middleware#assigning-middleware-to-routes
* Run ```php artisan vendor:publish``` - this will add a ```object_cache.php``` file to your project's config directory
* Add your Redis host to the app's .env (or localhost for local dev - ensure your environment has Redis installed)
* Edit the ```parameters``` and ```options``` values in ```config/object_cache.php``` - see https://github.com/nrk/predis/wiki/Connection-Parameters
* Also in ```config/object_cache.php``` add the class name with full namespace to the ```methodClass``` key

## Usage
To initialise a connection:
* Add ```use AdamCrampton\ObjectCache``` to the class
* Declare a variable to use for your connection, e.g. ```$redis = ObjectCache::init()```

Once initialised, you can use all Predis commands. See:
* http://squizzle.me/php/predis/doc/
* http://squizzle.me/php/predis/doc/Commands

## Middleware
The CheckObjectCache Middleware that ships with the package allows you to add cache objects to be automatically checked on each request, and if missing, set. You will need to add an ```$this->objects``` array (configuration) and each corresponding method to your cache store class (mentioned above in installation).

An array of 3 values are required for each item in the ```$this->objects``` array. They are:
* **cacheKey:** Name of the key for your object in the Redis store.
* **cacheTtl:** Using either a pre-defined named TTL (see below), or a TTL in seconds.
* **cacheMethod:** The name of the method to regenerate the cache data if not found (i.e. if it is missing or expired).

An example of an implementation using a cache store class:

```namespace App\ObjectCache;

use App\Models\PartPrice;
use AdamCrampton\ObjectCache\ObjectCache;
use AdamCrampton\ObjectCache\Middleware\CheckObjectCache;

class CacheMethodStore
{
    public $objects;
    
    public function __construct(ObjectCache $objectCache)
    {
        parent::__construct($objectCache);
    
        $this->objects = [
            [
                'cacheKey' => 'exampleKey',
                'cacheTtl' => $this->objectCache->ttl['hours']['twentyFour'],
                'cacheMethod' => 'setExample'
            ]
        ];
    }

    public function setExample()
    {
        return PartPrice::select(['part_id', 'price'])
            ->get()
            ->toJson();
    }
}
```

## Get Helper
Once initialised, you can fetch an object from the cache using the provided helper. The two parameters you need to pass in are the key and JSON decode option.

If you are passing in the decode option, set this to 'array' or 'object' for the correct return format. Example:

```
    /**
     * Fetch part data for the front end.
     *
     * @return array
     */
    protected function buildPartData(Request $request)
    {
        return ObjectCache::get('dealer_123456_part_data', 'array');
    }
```

## Pre-configured TTL
A convenience array of TTL values are included with the package. To use one of the values, initalise the Redis connection as mentioned above, and call the ```ttl``` property with the appropriate key name. Structure of this TTL array is as follows:

```$this->ttl = [
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


