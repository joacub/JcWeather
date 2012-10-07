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
            'accuweather' => array(
                'name' => 'AtWeather\Provider\AccuWeather',
                'params' => array(
                )
            ),
            'worldweatheronline' => array(
                'name' => 'AtWeather\Provider\WorldWeatherOnline',
                'params' => array(
                    'apiUrl' => 'http://free.worldweatheronline.com/feed/weather.ashx',
                    'apiKey' => '',
                    'format' => 'json',
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