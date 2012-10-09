<?php

return array(
    'atweather' => array(
        'providers' => array(
            'gismeteo' => array(
                'name' => 'AtWeather\Provider\Gismeteo',
                'params' => array(
                    'apiUrl' => 'http://informer.gismeteo.ru/xml/'
                )
            ),
            'yahoo' => array(
                'name' => 'AtWeather\Provider\Yahoo',
                'params' => array(
                    'apiUrl' => 'http://weather.yahooapis.com/forecastrss',
                    'units'  => ''
                )
            ),
            'worldweatheronline' => array(
                'name' => 'AtWeather\Provider\WorldWeatherOnline',
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
            'AtWeather' => 'AtWeather\Service\Factory',
            'AtWeatherProvider' => 'AtWeather\ProviderFactory',
        ),
    )
);