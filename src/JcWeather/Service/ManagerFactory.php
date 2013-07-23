<?php

namespace JcWeather\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use JcWeather\Manager;

/**
 * JcWeather service manager factory
 */
class ManagerFactory implements FactoryInterface
{
    /**
     * Factory method for JcWeather Manager service
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \JcWeather\Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $params = $config['at-weather'];

        $manager = new Manager($params);
        return $manager;
    }
}