<?php

namespace JcWeather\Provider;

/**
 * Gismeteo weather provider class for JcWeather.
 * @see http://www.gismeteo.ru/
 */
class Gismeteo extends AbstractProvider
{
    /**
     * Fetches the weather forecast for the given location.
     *
     * @return Gismeteo|mixed
     * @throws \Exception
     */
    public function fetch()
    {
        $uri = $this->getApiUrl() . $this->getLocation(). '_1.xml';

        $client = $this->getHttpClient();
        $client->setUri($uri);

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
        $forecastList = $xml->xpath('/MMWEATHER/REPORT/TOWN/FORECAST');

        $current = $forecastList[0];
        unset($forecastList[0]);
        $location = $xml->xpath('/MMWEATHER/REPORT/TOWN/@sname');

        $forecastArray = array();

        foreach ($forecastList as $day) {
            $dayData = array();

            $dayData['date'] = $day['day'] . '-' . $day['month'] . '-' . $day['year'] . ' ' . $day['hour'] . ':00' ;
            $dayData['low']  = $day->TEMPERATURE['min'];
            $dayData['high'] = $day->TEMPERATURE['max'];
            $dayData['icon'] = '';

            $forecastArray[] = $dayData;
        }

        $forecast = new \JcWeather\Forecast();
        $forecast->setLocation(urldecode($location[0]->sname))
            ->setCurrent(array(
                "icon" => '',
                "temperature" => $current->TEMPERATURE['min'] . ' - ' . $current->TEMPERATURE['max']))
            ->setForecast($forecastArray);

        $this->setForecast($forecast);

        return $this;
    }
}