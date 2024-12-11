<?php

namespace CpmsCommon\Queue\Adapter\Synchronous;

use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class SynchronousQueueAdapterFactory
 *
 * @package Queue\Adapter\Immediate
 */
class SynchronousQueueAdapterFactory
{
    /**
     * Create an object
     *
     * @param  ServiceLocatorInterface $container
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ServiceLocatorInterface $container)
    {
        return (new SynchronousQueueAdapter())->setServiceLocator($container);
    }
}
