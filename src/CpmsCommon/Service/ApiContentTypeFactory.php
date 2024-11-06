<?php

namespace CpmsCommon\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class ApiVersion
 *
 * @package CpmsCommon\Service
 */
class ApiContentTypeFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array */
        $config = $container->get('config');
        if (isset($config['api-tools-versioning']['default_version'])) {
            $version = $config['api-tools-versioning']['default_version'];
            $contentType = sprintf('Content-Type: application/vnd.dvsa-gov-uk.v%d+json; charset=UTF-8', $version);
        } else {
            $contentType = 'Content-Type: application/json; charset=UTF-8';
        }

        return $contentType;
    }
}
