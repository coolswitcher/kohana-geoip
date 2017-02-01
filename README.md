# kohana-geoip
Kohana 3.3 wrapper for MaxMind DB Database

Please install MaxMind-DB-Reader using composer or install C-extension
https://github.com/maxmind/MaxMind-DB-Reader-php

How to use

1. Create config file geoip.php and put him to config dir:
```
<?php defined('SYSPATH') or die('No direct script access.');
return array(
	'city'    => 'pathto/GeoLite2-City.mmdb',
	'country' => 'pathto/GeoLite2-Country.mmdb',
);
```

2. Example
```
$geoip = GeoIP::factory('24.24.24.24');

// get all raw data
var_dump($geoip->raw());

// get country code
echo $geoip->get_country_code();

// get country name
echo $geoip->get_country_name('en'); // english name
echo $geoip->get_country_name('ru'); // russian name

// get city name
echo $geoip->get_city_name('ru'); // russian name
```
