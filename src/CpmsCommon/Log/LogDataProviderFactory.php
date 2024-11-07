<?php

namespace CpmsCommon\Log;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class LogDataFactory
 * Injects the id of the logged in user and access token if available
 *
 * @package CpmsCommon\Log
 */
class LogDataProviderFactory implements FactoryInterface
{
    public const AUTH_SERVICE_ALIAS = 'cpms\service\authenticationService';

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logData = new LogData();
        if ($container->has(self::AUTH_SERVICE_ALIAS)) {
            /** @var \CpmsCommon\Service\BaseAuthenticationService $authService */
            $authService = $container->get(self::AUTH_SERVICE_ALIAS);

            /** @var string */
            $userId = $authService->getOptions()->getUser();
            $logData->setUserId($userId);
            /** @var string */
            $accessToken = $authService->getOptions()->getAccessToken();
            $logData->setAccessToken($accessToken);
        }

        return $logData;
    }
}
