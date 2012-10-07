<?php

namespace AtWeather\Provider;

use AtWeather;
use Zend\Http;

/**
 * AtWeather abstract provider class
 */
abstract class AbstractProvider implements AtWeather\ProviderInterface
{
    /**
     * Service API url
     */
    protected $apiUrl;

    /**
     * The HTTP Client object
     *
     * @var \Zend\Http\Client
     */
    protected $httpClient;

    /**
     * Container for the obtained forecast
     *
     * @var array $forecast
     */
    protected $forecast;

    /**
     * The location of weather forecast
     *
     * @var string $location
     */
    private $location;

    /**
     * @param $location
     * @param array $params
     */
    public function __construct($location, $params = array())
    {
        $this->setApiUrl($params['apiUrl']);
        $this->setHttpClient(new Http\Client($this->getApiUrl()));

        if (!empty($location)) {
            $this->setLocation($location);
        }
    }

    /**
     * Setter for the Google's service API URL
     *
     * @param string $url
     */
    public function setApiUrl($url)
    {
        $this->apiUrl = $url;
    }

    /**
     * Getter for the Google's service API URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the Zend_Http_Client object to use in requests. If not provided a default will
     * be used.
     *
     * @param \Zend\Http\Client $client
     * @return \AtWeather\Provider\WorldWeatherOnline
     */
    public function setHttpClient(Http\Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Returns the instance of the Zend\Http\Client which will be used. Creates an instance
     * of Zend\Http\Client if no previous client was set.
     *
     * @return \Zend\Http\Client The HTTP client which will be used
     */
    public function getHttpClient()
    {
        if (!($this->httpClient instanceof Http\Client)) {
            $client = new Http\Client();
            $this->setHttpClient($client);
        }

        $this->httpClient->resetParameters();
        return $this->httpClient;
    }

    /**
     * Setter for the forecast
     *
     * @param \AtWeather\Forecast $forecast
     */
    public function setForecast(AtWeather\Forecast $forecast)
    {
        $this->forecast = $forecast;
    }

    /**
     * Getter for the forecast
     *
     * @return string
     */
    public function getForecast()
    {
        if (is_null($this->forecast)) {
            $this->fetch();
        }

        return $this->forecast;
    }

    /**
     * Sets the city name to be used in requests
     *
     * @param $location
     * @return AbstractProvider
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Gets the city name to be used in requests.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Fetches the weather forecast for the given location.
     *
     * @return mixed
     */
    abstract public function fetch();
}