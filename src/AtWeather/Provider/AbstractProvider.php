<?php

namespace AtWeather\Provider;

use Zend\Http;

/**
 * AtWeather abstract provider class
 */
abstract class AbstractProvider implements ProviderInterface
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
        if (!isset($params['apiUrl'])) {
            throw new \Exception('Please specify API url for given provider.');
        }

        $this->setApiUrl($params['apiUrl']);
        $this->setHttpClient(new Http\Client($this->getApiUrl()));

        if (!empty($location)) {
            $this->setLocation($location);
        }
    }

    /**
     * @param $url
     * @return \AtWeather\Provider\AbstractProvider
     */
    public function setApiUrl($url)
    {
        $this->apiUrl = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the city name to be used in requests
     *
     * @param $location
     * @return \AtWeather\Provider\AbstractProvider
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
     * Sets the \Zend\Http\Client object to use in requests. If not provided a default will
     * be used.
     *
     * @param \Zend\Http\Client $client
     * @return \AtWeather\Provider\AbstractProvider
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
     * @param \AtWeather\Forecast $forecast
     * @return \AtWeather\Provider\AbstractProvider
     */
    public function setForecast(\AtWeather\Forecast $forecast)
    {
        $this->forecast = $forecast;
        return $this;
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
     * Fetches the weather forecast for the given location.
     *
     * @return mixed
     */
    abstract public function fetch();
}