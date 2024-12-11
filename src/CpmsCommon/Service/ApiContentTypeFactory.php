<?php

namespace CpmsCommon\Service;

use Psr\Container\ContainerInterface;

/**
 * Class ApiVersion
 *
 * @package CpmsCommon\Service
 */
class ApiContentTypeFactory
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var array $config */
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
