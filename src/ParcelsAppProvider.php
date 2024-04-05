<?php

namespace locky42\ParcelsAppProvider;

class ParcelsAppProvider
{
    const URL_SHIPMENTS_TRACKING = 'shipments/tracking';

    protected $apiUrl = 'https://parcelsapp.com/api';
    protected $version = 'v3';
    protected string $apiKey;

    public string $language = 'en';
    public string $country = 'United States';

    /**
     * @param string $apiKey
     * @param $country
     * @param $language
     */
    public function __construct(string $apiKey, $country = null, $language = null)
    {
        $this->apiKey = $apiKey;

        if ($language)
            $this->language = $language;

        if ($country)
            $this->country = $country;
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
     * @return mixed
     */
    public function getTrackingRequest($trackingId, $country = null, $language = null): mixed
    {
        if ($country)
            $this->country = $country;
        if ($language)
            $this->language = $language;

        $trackingRequest = $this->createTracking($trackingId);

        if (isset($trackingRequest['uuid'])) {
            $requestData = [
                'uuid' => $trackingRequest['uuid'],
                'apiKey' => $this->apiKey
            ];
            return $this->sendRequest($requestData, null, false);
        } else {
            return $trackingRequest;
        }
    }

    public static function getTracking($apiKey, $trackingId, $country = null, $language = null): mixed
    {
        $trackingRequest = new self($apiKey, $language, $country);
        return $trackingRequest->getTrackingRequest($trackingId);
    }

    /**
     * @param $trackingId
     * @param $country
     * @return mixed
     */
    public function createTracking($trackingId, $country = null, $language = null): mixed
    {
        $requestData = [
            'shipments' => [
                'trackingId' => $trackingId,
                'destinationCountry' => $country ?? $this->country
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
     */
    protected function sendRequest($data = null, $url = null , $isPost = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getApiUrl() . '/' . $url ?? self::URL_SHIPMENTS_TRACKING);
        curl_setopt($ch, CURLOPT_POST, $isPost);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
