<?php

namespace locky42\ParcelsAppProvider;

use locky42\ParcelsAppProvider\exceptions\ParcelsAppProviderError;

class ParcelsAppProvider
{
    const URL_SHIPMENTS_TRACKING = 'shipments/tracking';

    protected $apiUrl = 'https://parcelsapp.com/api';
    protected $version = 'v3';
    protected string $apiKey;

    public string $language = 'en';
    public string $country = 'United States';
    public string|int|null $zipCode = null;

    /**
     * @param string $apiKey
     * @param $country
     * @param $language
     */
    public function __construct(string $apiKey, $country = null, $zipCode = null, $language = null)
    {
        $this->apiKey = $apiKey;

        if ($language)
            $this->language = $language;

        if ($country)
            $this->country = $country;

        if ($zipCode)
            $this->zipCode = $zipCode;
    }

    /**
     * @param string $language
     * @return void
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    public function setZipCode(string|int $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl . '/' . $this->version;
    }

    /**
     * @param $trackingId
     * @param $country
     * @param $zipCode
     * @param $language
     * @return mixed
     * @throws ParcelsAppProviderError
     */
    public function getTrackingRequest($trackingId, $country = null, $zipCode = null, $language = null): mixed
    {
        if ($country)
            $this->country = $country;
        if ($zipCode)
            $this->zipCode = $zipCode;
        if ($language)
            $this->language = $language;

        $trackingRequest = $this->createTracking($trackingId);

        if (isset($trackingRequest['uuid'])) {
            $requestData = [
                'uuid' => $trackingRequest['uuid'],
                'apiKey' => $this->apiKey
            ];
            return $this->sendRequest($requestData, null, false);
        } elseif (isset($trackingRequest['error'])) {
            throw new ParcelsAppProviderError($trackingRequest['error'], $trackingRequest['description'] ?? null);
        } else {
            return $trackingRequest;
        }
    }

    /**
     * @param $trackingId
     * @param $country
     * @param $zipCode
     * @param $language
     * @return mixed
     * @throws ParcelsAppProviderError
     */
    public function createTracking($trackingId, $country = null, $zipCode = null, $language = null): mixed
    {
        $shipment = [
            'trackingId' => $trackingId,
            'destinationCountry' => $country ?? $this->country
        ];

        $zipCode = $zipCode ?? $this->zipCode;
        if ($zipCode)
            $shipment['zipcode'] = $zipCode;

        $requestData = [
            'shipments' => [
                $shipment
            ],
            'language' => $language ?? $this->language,
            'apiKey' => $this->apiKey
        ];

        return $this->sendRequest($requestData);
    }

    /**
     * @param $data
     * @param $url
     * @param $isPost
     * @return mixed
     * @throws ParcelsAppProviderError
     */
    protected function sendRequest($data = null, $url = null , $isPost = true)
    {
        $ch = curl_init();

        $url = $this->getApiUrl() . '/' . ($url ?? self::URL_SHIPMENTS_TRACKING);
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        } else {
            $url .= '?' . http_build_query($data);
            $data = null;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);

        if ($headers['http_code'] !== 200) {
            throw new ParcelsAppProviderError($response, null, $headers['http_code']);
        }

        return json_decode($response, true);
    }

    /**
     * @param $apiKey
     * @param $trackingId
     * @param $country
     * @param $zipCode
     * @param $language
     * @return mixed
     * @throws ParcelsAppProviderError
     */
    public static function getTracking($apiKey, $trackingId, $country = null, $zipCode = null, $language = null): mixed
    {
        $trackingRequest = new self($apiKey, $country, $zipCode, $language);
        return $trackingRequest->getTrackingRequest($trackingId);
    }
}
