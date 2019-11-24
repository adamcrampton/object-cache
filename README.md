# object-cache
Laravel object cache package using Predis

## Installation
* ```composer require adamcrampton/object-cache```
* Add library service provider and facade to **config/app.php**
* Add library middleware to **app/Http/Kernel.php** (optional)
* Configure your routes to use middleware (optional)
* Run ```php artisan vendor:publish```
* Add your Redis host to the app's .env (or localhost for local dev - ensure your environment has Redis installed)
* Edit your settings in **config/object_cache.php**

## Usage
To initialise a connection:
* Add ```use AdamCrampton\ObjectCache``` to the class
* Declare a variable to use for your connection, e.g. ```$redis = ObjectCache::init()```

Once initialised, you can use all Predis commands. See:
* http://squizzle.me/php/predis/doc/
* http://squizzle.me/php/predis/doc/Commands

## Middleware
The CheckObjectCache Middleware that ships with the package allows you to add cache objects to be automatically checked on each request, and if missing, set.

An array of 3 values are required for each item. They are:
* **cacheKeyTtl:** Name of the key for your object in the Redis store.
* **cacheTTl:** Using either a pre-defined named TTL (see below), or a TTL in seconds.
* **cacheMethod:** The name of the method to regenerate the cache data if not found (i.e. if it is missing or expired).

## Pre-configured TTL
A convenience array of TTL values are included with the package. To use one of the values, initalise the Redis connection as mentioned above, and call the **ttl** property with the appropriate key name. Structure of this TTL array is as follows:

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
];```


