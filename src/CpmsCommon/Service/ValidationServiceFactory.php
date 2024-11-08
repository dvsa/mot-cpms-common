<?php

namespace CpmsCommon\Service;

use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class ValidationServiceFactory
 *
 * @author Phil Burnett <phil.burnett@valtech.co.uk>
 */
class ValidationServiceFactory
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $container
     * @return ValidationService
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ServiceLocatorInterface $container)
    {
        $validationService = new ValidationService();
        $validationService->setServiceLocator($container);

        return $validationService;
    }
}
