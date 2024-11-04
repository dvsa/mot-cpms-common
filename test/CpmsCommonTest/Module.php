<?php

/**
 * @package      CPMS Payment
 * @subpackage   controller
 * @author       Pele Odiase <pele.odiase@valtech.co.uk>
 */

namespace CpmsCommonTest;

use CpmsCommon\View\JsonExceptionStrategy;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;

class Module
{
    /**
     * Bootstrap event
     *
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application         = $event->getApplication();
        $eventManager        = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $config              = $application->getServiceManager()->get('Config');
        $displayExceptions   = $config['display_exception'];
        $exceptionStrategy   = new JsonExceptionStrategy();

        $exceptionStrategy->setDisplayExceptions($displayExceptions);
        $moduleRouteListener->attach($eventManager);
        $exceptionStrategy->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../test.global.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Laminas\Console' => realpath('./src/Laminas/Console'),
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),

        );
    }
}
