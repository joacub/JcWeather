<?php

namespace JcWeather;

use JcWeather\Service;
use Nette\Diagnostics\Debugger;

class Manager
{
    /**
     * @var Array
     */
    protected $params;

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * Set the Module specific configuration parameters
     *
     * @param $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Get the forecast for given location
     *
     * @param string $cityName
     * @param string $language
     * @return array
     * @throws \Exception
     */
    public function getWeather($location, $provider, $params = array())
    {
        if (isset($this->cache[$location])) {
            $weather = $this->cache[$location];
        } else {
        	
        	$this->params['providers'][$provider]['params'] = $params + $this->params['providers'][$provider]['params'];
            $providerFactory = new Service\ProviderFactory();
            $provider = $providerFactory->createProvider($provider, $this->params);
            $provider->setLocation($location);

            // Cache the weather object so we don't have to do HTTP request on each call
            // Enables to get multiple object's properties at different times
            $weather = $provider->getForecast();
            $this->cache[$location] = $weather;
        }

        return $weather;
    }
}