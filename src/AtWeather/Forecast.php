<?php

namespace AtWeather;

class Forecast
{
    /**
     * Forecast location
     *
     * @var string
     */
    protected $location;

    /**
     * Current forecast
     *
     * @var array
     */
    protected $current = array();

    /**
     * Forecast for days
     *
     * @var array
     */
    protected $forecast = array();

    /**
     * @param $data
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return array
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param $data
     */
    public function setCurrent($data)
    {
        $this->current = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param $data
     */
    public function setForecast($data)
    {
        $this->forecast = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getForecast()
    {
        return $this->forecast;
    }

    /**
     * Get url to the weather image
     *
     * @return string
     */
    public function getCurrentIcon()
    {
        return $this->current['icon'];
    }

    /**
     * Get current temperature
     *
     * @return string
     */
    public function getCurrentTemperature()
    {
        return $this->current['temperature'];
    }

    /**
     * Get url for the weather image for a given day in array
     *
     * @param type $dayNumber
     * @return string
     */
    public function getForecastIcon($dayNumber)
    {
        return $this->forecast[$dayNumber]['icon'];
    }

    /**
     * Get day's lowest temperature for a given day in array
     *
     * @param type $dayNumber
     * @return string
     */
    public function getForecastLow($dayNumber)
    {
        return $this->forecast[$dayNumber]['low'];
    }

    /**
     * Get day's highest temperature for a given day in array
     *
     * @param type $dayNumber
     * @return string
     */
    public function getForecastHigh($dayNumber)
    {
        return $this->forecast[$dayNumber]['high'];
    }
}