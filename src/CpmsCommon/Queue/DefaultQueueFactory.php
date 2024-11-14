<?php

namespace CpmsCommon\Queue;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class DefaultQueueFactory
 *
 * @package Queue
 */
class DefaultQueueFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  null|string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');
        if (empty($config['default_queue_adapter'])) {
            throw new RuntimeException('Default queue adapter not configured');
        }
        $defaultQueue = $config['default_queue_adapter'];
        if (!isset($config['queue_adapters'][$defaultQueue]['class'])) {
            throw new RuntimeException('Class for default queue not set');
        }
        $queueAdapter = $container->get($config['queue_adapters'][$defaultQueue]['class']);
        if ($queueAdapter instanceof QueueInterface === false) {
            throw new RuntimeException('Could not load Queue adapter');
        }

        return $queueAdapter;
    }
}
