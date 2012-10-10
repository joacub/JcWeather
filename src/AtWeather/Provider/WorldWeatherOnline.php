<?php

namespace AtWeather\Provider;

use AtWeather;
use Zend\Json\Json;

/**
 * WorldWeatherOnline weather provider class for AtWeather.
 *
 * @see http://www.worldweatheronline.com/
 */
class WorldWeatherOnline extends AbstractProvider
{
    /**
     * Service API key
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $responseFormat = 'json';

    /**
     * @var int
     */
    protected $numberOfDays = 5;

    /**
     * @param $location
     * @param array $params
     */
    public function __construct($location, $params = array())
    {
        parent::__construct($location, $params);

        if (!isset($params['key'])) {
            throw new \Exception('Please specify API Key.');
        }

        $this->setApiKey($params['key']);

        if (isset($params['format'])) {
            $this->setResponseFormat($params['format']);
        }

        if (isset($params['num_of_days'])) {
            $this->setNumberOfDays($params['num_of_days']);
        }
    }

    /**
     * @param $key
     * @return \AtWeather\Provider\WorldWeatherOnline
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
        return $this;
    }

    /**
     * Getter for the Google's service API URL
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $format
     * @return \AtWeather\Provider\WorldWeatherOnline
     */
    public function setResponseFormat($format)
    {
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseFormat()
    {
        return $this->responseFormat;
    }

    /**
     * @param $value
     * @return \AtWeather\Provider\WorldWeatherOnline
     */
    public function setNumberOfDays($value)
    {
        $this->numberOfDays = (int) $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfDays()
    {
        return $this->numberOfDays;
    }

    /**
     * Fetches the weather forecast for the given location.
     *
     * @return \AtWeather\Provider\WorldWeatherOnline
     * @throws \Exception
     */
    public function fetch()
    {
        $params = array(
            'key'         => $this->getApiKey(),
            'q'           => $this->getLocation(),
            'format'      => $this->getResponseFormat(),
            'num_of_days' => $this->getNumberOfDays()
        );

        // @todo: Parameters format and num_of_days are not required.
        // By default are xml and 1

        $client = $this->getHttpClient();
        $client->setParameterGet($params);

        $response = null;

        try {
            $response = $client->send();
        } catch(\Exception $e) {
            throw new \Exception("No weather information available.");
        }

        if ($response->getStatusCode() != 200) {
            throw new \Exception("No weather information available.");
        }

        $data = Json::decode($response->getBody());
        $current = $data->data->current_condition[0];
        $forecastList = $data->data->weather;

        $forecastArray = array();
        foreach ($forecastList as $day) {
            $dayData = array();
            $dayData['date'] = $day->date;
            $dayData['low']  = $day->tempMinC;
            $dayData['high'] = $day->tempMaxC;
            $dayData['icon'] = $day->weatherIconUrl[0]->value;

            $forecastArray[] = $dayData;
        }

        $forecast = new AtWeather\Forecast();
        $forecast->setLocation($data->data->request[0]->query)
                 ->setCurrent(array(
                     "icon" => $current->weatherIconUrl[0]->value,
                     "temperature" => $current->temp_C))
                 ->setForecast($forecastArray);

        $this->setForecast($forecast);

        return $this;
    }
}