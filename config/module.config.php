<?php

return array(
    'at-weather' => array(
        'providers' => array(
            'gismeteo' => array(
                'name' => 'JcWeather\Provider\Gismeteo',
                'params' => array(
                    'apiUrl' => 'http://informer.gismeteo.ru/xml/'
                )
            ),
            'yahoo' => array(
                'name' => 'JcWeather\Provider\Yahoo',
                'params' => array(
                    'apiUrl' => 'http://weather.yahooapis.com/forecastrss',
                    'units'  => ''
                )
            ),
            'worldweatheronline' => array(
                'name' => 'JcWeather\Provider\WorldWeatherOnline',
                'params' => array(
                    'apiUrl'      => 'http://free.worldweatheronline.com/feed/weather.ashx',
                    'apiKey'      => '',
                    'format'      => 'json',
                    'num_of_days' => 5
                )
            )
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'JcWeatherManager' => 'JcWeather\Service\ManagerFactory',
            'JcWeatherProvider' => 'JcWeather\Service\ProviderFactory',
        ),
    )
);