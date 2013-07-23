<?php

namespace JcWeather\Provider;

use Nette\Diagnostics\Debugger;
/**
 * Yahoo! weather provider class for JcWeather.
 *
 * @see http://weather.yahoo.com/
 * @see http://developer.yahoo.com/weather/
 */
class Yahoo extends AbstractProvider
{
    /**
     * Indicates the degree units for the weather forecast.
     * By default, Yahoo! Weather returns temperature information in degrees Fahrenheit.
     *
     * @var string
     */
    protected $units;

    /**
     * @param $location
     * @param array $params
     */
    public function __construct($location, $params = array())
    {
        parent::__construct($location, $params);

        if (isset($params['units'])) {
            $this->setUnits($params['units']);
        }
    }

    /**
     * @param $value
     * @return \JcWeather\Provider\Yahoo
     */
    public function setUnits($value)
    {
        $value = strtolower($value);
        if ( in_array($value, array('c', 'f')) ) {
            $this->units = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Fetches the weather forecast for the given location.
     *
     * @return Yahoo|mixed
     * @throws \Exception
     */
    public function fetch()
    {
        $uri = $this->getApiUrl();

        $client = $this->getHttpClient();
        $client->setUri($uri);
        $client->setParameterGet(array('w' => $this->getLocation(), 'u' => $this->getUnits()));

        $response = null;

        try {
            $response = $client->send();
        } catch(\Exception $e) {
            throw new \Exception("No weather information available.");
        }

        if ($response->getStatusCode() != 200) {
            throw new \Exception("No weather information available.");
        }

        $xml = new \SimpleXMLElement(utf8_encode($response->getBody()));

        $forecast = new \JcWeather\Forecast();
        
        $forecast->setLocation((string) $xml->channel->xpath('yweather:location')[0]->attributes()->city)
        ->setCurrent(array(
        	"icon" => 'http://l.yimg.com/a/i/us/we/52/'.( (string) $xml->channel->item->xpath('yweather:condition')[0]->attributes()->code).'.gif',
        	"temperature" => (string) $xml->channel->item->xpath('yweather:condition')[0]->attributes()->temp))
        	->setForecast($xml->channel->item->xpath('yweather:forecast'));
        
        $this->setForecast($forecast);

        return $this;
    }
}