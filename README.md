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
* Declare something like ```$redis = ObjectCache::init()```

Once initialised, you can use all Predis commands. See:
* http://squizzle.me/php/predis/doc/
* http://squizzle.me/php/predis/doc/Commands

## Middleware
* //TODO: Add to readme
