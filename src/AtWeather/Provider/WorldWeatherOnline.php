<?php

namespace AtWeather\Provider;

use AtWeather;
use Zend\Json\Json;

/**
 * AtWeather WorldWeatherOnline provider
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
     * @param $location
     * @param array $params
     */
    public function __construct($location, $params = array())
    {
        parent::__construct($location, $params);

        $this->setApiKey($params['apiKey']);
        $this->setResponseFormat($params['format']);
    }

    /**
     * Setter for service API key
     *
     * @param $key
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
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
     */
    public function setResponseFormat($format)
    {
        $this->responseFormat = $format;
    }

    /**
     * @return string
     */
    public function getResponseFormat()
    {
        return $this->responseFormat;
    }

    /**
     * Fetches the weather forecast for the given location.
     *
     * @return WorldWeatherOnline
     * @throws \Exception
     */
    public function fetch()
    {
        $params = array(
            'key'         => $this->getApiKey(),
            'format'      => $this->getResponseFormat(),
            'q'           => $this->getLocation(),
            'num_of_days' => 5
        );

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