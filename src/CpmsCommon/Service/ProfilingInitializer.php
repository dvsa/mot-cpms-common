<?php

/**
 * Profiling Initializer
 *
 * @author Jakub Igla <jakub.igla@valtech.co.uk>
 */

namespace CpmsCommon\Service;

use Interop\Container\ContainerInterface;
use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\ServiceManager\Initializer\InitializerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProfilingInitializer
 *
 * @package CpmsCommon\Service
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class ProfilingInitializer implements InitializerInterface
{
    const CLASS_PATH = __CLASS__;

    const CONFIG_KEY_PROFILE_ENABLED = 'cpms_profiler_enabled';

    /**
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        $this($serviceLocator, $instance);
    }

    /**
     * Initialize the given instance
     *
     * @param  ContainerInterface $container
     * @param  object $instance
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof EventManagerAwareInterface) {
            return;
        }

        $config = $container->get('Config');

        if (!isset($config[self::CONFIG_KEY_PROFILE_ENABLED])) {
            $config[self::CONFIG_KEY_PROFILE_ENABLED] = false;
        }

        if ($config[self::CONFIG_KEY_PROFILE_ENABLED] == false) {
            return;
        }

        $instance->getEventManager()->attach(
            '*',
            function (Event $event) use ($container) {

                $logger = $container->get('Logger');

                $exploded  = explode('.', $event->getName());
                $eventName = $exploded[0];

                $queueLabel = 'invoked';

                if (isset($exploded[1])) {
                    switch ($exploded[1]) {
                        case 'pre':
                            $queueLabel = 'started';
                            break;
                        case 'post':
                            $queueLabel = 'finished';
                            break;
                    }
                }

                $target = get_class($event->getTarget());
                $params = json_encode($event->getParams());

                $logger->debug(
                    sprintf(
                        '%s called on %s, using params %s %s on %s',
                        $eventName,
                        $target,
                        $params,
                        $queueLabel,
                        microtime()
                    )
                );
            }
        );
    }
}
