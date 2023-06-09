<?php
namespace CpmsCommon\Log\Formatter;

use CpmsCommon\Log\LogData;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for Stream Log Writer
 *
 * @package       CpmsCommon
 * @subpackage    Log
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class DvsaFormatterFactory implements FactoryInterface
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
        $config       = $container->get('config')['logger'];
        $replacements = $container->get($config['replacement']);
        $formatter    = new DvsaLogFormatter($config['dateTimeFormat']);

        if ($replacements instanceof LogData) {
            $replacements->setStrictMode(false);
            $formatter->setLogData($replacements);
        }

        return $formatter;
    }
}
