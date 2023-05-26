<?php
namespace CpmsCommon\Service;

use CpmsCommon\Log\LogData;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for Common Logger
 *
 * @package       CpmsCommon
 * @subpackage    Service
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class LoggerServiceFactory implements FactoryInterface
{
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
        /** @var LogData $logData */
        $log           = new LoggerService();
        $serviceConfig = $container->get('config');
        $logData       = null;
        $writers       = (array)$serviceConfig['logger']['writers'];
        $writers       = array_unique($writers);

        if (!empty($serviceConfig['logger']['replacement'])) {
            $logData = $container->get($serviceConfig['logger']['replacement']);
        }

        if ($logData and $logData instanceof LogData) {
            $logData->setStrictMode(false);
            $log->setLogData($logData);
        }

        foreach ($writers as $logWriter) {
            $log->addWriter($container->get($logWriter));
        }

        return $log;
    }
}
