Caching
=======

[![Build status on GitHub](https://github.com/xp-forge/caching/workflows/Tests/badge.svg)](https://github.com/xp-forge/caching/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/caching/version.png)](https://packagist.org/packages/xp-forge/caching)

Data caching.

```php
use util\data\Caching;

// Select storage
$caching= Caching::inFileSystem('.');
$caching= Caching::inMemory();
$caching= Caching::inRedis('redis://localhost');

// Configure default entry lifetime
$cache= $caching->withTTL(3600);

// Limit cache size
$cache= $caching->keepMRU(100);
$cache= $caching->limitedTo(1024 * 1024);
```

```php
use util\data\Caching;
use util\Date;

$cache= Caching::inFileSystem('.')->withTTL(3600);
$cache->store('key', 'value');

// Will yield the stored value
$value= $cache->retrieve('key');

// Will yield NULL
sleep(3600);
$value= $cache->retrieve('key');

// Removes any cached item and returns it
$removed= $cache->remove('key');

// Will invoke the function as there is no cached item
$value= $cache->item('key', fn($key) => $database->fetch('config.'.$key));
```

```php
$cache->store('short-term', 'value', ttl: 300);
$cache->store('dayfly', 'value', until: new Date('tomorrow'));

// Prolong key's lifetime 
$cache->update('key');
```