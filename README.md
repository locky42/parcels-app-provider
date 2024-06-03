# ParcelsApp PHP Provider


## Using
    
```php
$provider = new \locky42\ParcelsAppProvider\ParcelsAppProvider('api-key');

/** optional */
$provider->setCountry('country');

/** optional */
$provider->setZipCode('zipCode');

/** optional */
$provider->setLanguage('language');

try {
    $trackingData = $provider->getTrackingRequest('tracking-id');
} catch (\locky42\ParcelsAppProvider\exception\ParcelsAppException $e) {
    echo $e->getMessage();
}
...
```
or
```php
$trackingData = $provider->getTrackingRequest('tracking-id', 'country', 'zipCode', 'language');
```
or
```php
$provider = new \locky42\ParcelsAppProvider\ParcelsAppProvider('api-key', 'country', 'zipCode', 'language');
```
or
```php
$trackingData = \locky42\ParcelsAppProvider\ParcelsAppProvider::getTrackingRequest('api-key', 'tracking-id', 'country', 'zipCode', 'language');
```

Default country is 'United States', default language is 'en'.

## Api Documentation
[ParcelsApp Home Page](https://parcelsapp.com/)

[ParcelsApp API Documentation](https://parcelsapp.com/api-docs/)