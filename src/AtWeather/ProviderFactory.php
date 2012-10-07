<?php

namespace AtWeather;

/**
* AtWeather provider factory
*/
class ProviderFactory
{
    /**
     * Factory method for creating weather provider
     *
     * @param $name
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function createProvider($name, $params)
    {
        if (!isset($params['providers'][$name])) {
            throw new \Exception('No weather provider with given name "' . $name . '"');
        }
        $providerData = $params['providers'][$name];

        $providerClassName = $providerData['name'];
        $providerParams    = $providerData['params'];

        $provider = new $providerClassName(null, $providerParams);

        return $provider;
    }
}