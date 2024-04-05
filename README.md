# ParcelsApp PHP Provider


## Using
    
```php
$provider = new \locky42\ParcelsAppProvider\ParcelsAppProvider('api-key');

try {
    $trackingData = $provider->getTrackingRequest('tracking-id');
} catch (\locky42\ParcelsAppProvider\exception\ParcelsAppException $e) {
    echo $e->getMessage();
}
...
```
or
```php
$trackingData = $provider->getTrackingRequest('tracking-id', 'country', 'language');
```
or
```php
$provider = new \locky42\ParcelsAppProvider\ParcelsAppProvider('api-key', 'country', 'language');
```
or
```php
$trackingData = \locky42\ParcelsAppProvider\ParcelsAppProvider::getTrackingRequest('api-key', 'tracking-id', 'country', 'language');
```

Default country is 'United States', default language is 'en'.

## Api Documentation
[ParcelsApp Home Page](https://parcelsapp.com/)

[ParcelsApp API Documentation](https://parcelsapp.com/api-docs/)