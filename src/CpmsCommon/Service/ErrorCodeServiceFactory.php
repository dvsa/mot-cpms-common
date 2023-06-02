<?php
namespace CpmsCommon\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for Error Codes
 *
 * @package       CpmsCommon
 * @subpackage    Service
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class ErrorCodeServiceFactory implements FactoryInterface
{
    /**
     * Creates the error coder service class
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return ErrorCodeService
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig  = $container->get('config');
        $class          = $serviceConfig['error_code']['provider'];
        $customMessages = (array)$serviceConfig['error_code']['messages'];

        /** @var ErrorCodeService $service */
        $service = new $class($customMessages);

        return $service;
    }
}
