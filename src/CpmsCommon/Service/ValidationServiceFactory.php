<?php

namespace CpmsCommon\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class ValidationServiceFactory
 *
 * @author Phil Burnett <phil.burnett@valtech.co.uk>
 */
class ValidationServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return ValidationService
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $validationService = new ValidationService();
        $validationService->setServiceLocator($container);

        return $validationService;
    }
}
