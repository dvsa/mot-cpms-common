<?php

namespace CpmsCommon\Service;

use DvsaLogger\Contract\IdentityProviderInterface;
use DvsaLogger\Contract\TokenServiceInterface;
use DvsaLogger\Factory\MotLoggerFactory;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for Common Logger
 *
 * Builds a LoggerService from the named 'mot-cpms-common' logger defined under
 * 'mot_logger' => 'loggers' in the merged application config.
 *
 * @package       CpmsCommon
 * @subpackage    Service
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class LoggerServiceFactory implements FactoryInterface
{
    public const LOGGER_NAME = 'mot-cpms-common';

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
    #[\Override]
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = (array) $container->get('config');
        $motLoggerConfig = (array) ($serviceConfig['mot_logger'] ?? []);
        $loggers = $motLoggerConfig['loggers'] ?? [];
        $namedConfig = is_array($loggers) ? (array) ($loggers[self::LOGGER_NAME] ?? []) : [];

        $identityProvider = null;
        if ($container->has(IdentityProviderInterface::class)) {
            $candidate = $container->get(IdentityProviderInterface::class);
            if ($candidate instanceof IdentityProviderInterface) {
                $identityProvider = $candidate;
            }
        }

        $tokenService = null;
        if ($container->has(TokenServiceInterface::class)) {
            $candidate = $container->get(TokenServiceInterface::class);
            if ($candidate instanceof TokenServiceInterface) {
                $tokenService = $candidate;
            }
        }

        $includeToken = (bool) ($namedConfig['include_token'] ?? $motLoggerConfig['include_token'] ?? false);

        $motLogger = (new MotLoggerFactory(null, null, $container))($container, self::LOGGER_NAME, $options);

        return new LoggerService(
            $motLogger->getLogger(),
            $identityProvider,
            $tokenService,
            $motLogger->getRequestUuid(),
            $includeToken,
        );
    }
}
