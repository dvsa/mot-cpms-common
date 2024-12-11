<?php
/**
 * CPMS Common Module
 * Contains code shared between all OLCS modules
 */
namespace CpmsCommon;

use Laminas\Mvc\Controller\PluginManager;
use Laminas\Mvc\Router\RouteMatch;

/**
 * Class Module
 *
 * @package CpmsCommon
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($controller, $pluginManager) {
                    /** @var PluginManager $pluginManager */
                    if ($controller instanceof ContentTypeAwareInterface) {
                        $locator = $pluginManager->getServiceLocator();
                        $config = $locator->get('config');
                        $versions = [];
                        if (isset($config['api-tools-versioning']['default_version'])) {
                            $versions[] = $config['api-tools-versioning']['default_version'];
                        }

                        /** @var RouteMatch $routeMatch */
                        $routeMatch = $locator->get('Application')->getMvcEvent()->getRouteMatch();

                        if ($routeMatch) {
                            $versions[] = (int)$routeMatch->getParam('version');
                        }

                        $versions = array_unique(array_filter($versions));

                        foreach ($versions as $version) {
                            $contentType = sprintf('application/vnd.dvsa-gov-uk.v%d+json', $version);
                            $controller->setCustomContentType($contentType);
                        }
                    }
                }
            )
        );
    }
}
