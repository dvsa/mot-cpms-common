<?php

namespace CpmsCommonTest\Controller\Plugin;

use CpmsCommon\Controller\Plugin\SendPayload;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleController;
use Laminas\Http\Request;
use Laminas\Router\Http\RouteMatch;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class SampleControllerTest
 *
 * @package CpmsCommonTest\Controller
 */
class SendPayloadTest extends AbstractControllerTestCase
{
    /**
     * @var SampleController
     */
    private $controller;

    /** @var SendPayload */
    private $plugin;

    public function setUp(): void
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        $serviceManager = Bootstrap::getInstance()->getServiceManager();
        /** @var array */
        $applicationConfig = $serviceManager->get('ApplicationConfig');

        $this->setApplicationConfig($applicationConfig);
        $loader = $this->getApplicationServiceLocator()->get('ControllerManager');
        $this->controller = $loader->get('CpmsCommonTest\Sample');
        $this->controller->setServiceLocator($this->getApplicationServiceLocator());
        $request = new Request();
        $this->controller->dispatch($request);
        $serviceManager->setAllowOverride(true);
        parent::setUp();
        $this->plugin = new SendPayload();
    }

    public function testVersionInPayload(): void
    {
        $result = $this->controller->sendPayload([]);
        $payload = $result->getVariables();
        $this->assertArrayNotHasKey(SendPayload::API_VERSION_KEY, $payload);

        $version = 7;
        $config ['api-tools-versioning']['default_version'] = $version;
        /** @var array */
        $result = $this->plugin->setApiVersion([], $config, null);
        $this->assertArrayHasKey(SendPayload::API_VERSION_KEY, $result);
        $this->assertSame($version, $result[SendPayload::API_VERSION_KEY]);

        $version = 10;
        $routeMatch = new RouteMatch(['version' => $version]);
        /** @var array */
        $result = $this->plugin->setApiVersion([], [], $routeMatch);
        $this->assertArrayHasKey(SendPayload::API_VERSION_KEY, $result);
        $this->assertSame($version, $result[SendPayload::API_VERSION_KEY]);
    }
}
