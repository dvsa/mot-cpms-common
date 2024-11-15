<?php

namespace CpmsCommonTest\Mock;

use CpmsCommon\Utility\LoggerAwareInterface;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\ServiceManager\ServiceLocatorInterface;

class LoggerAwareTraitMock implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ServiceLocatorInterface $serviceLocator;

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoggerAwareTraitMock
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }
}
