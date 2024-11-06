<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\Controller\Plugin\SendPayload;
use CpmsCommon\Service\ErrorCodeService;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleController;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class ControllerPluginTest extends TestCase
{
    /** @var  ServiceManager */
    protected $serviceManager;

    /** @var ErrorCodeService $errorService */
    private $errorService;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();

        /** @var ErrorCodeService */
        $errorCodeService = $this->serviceManager->get('cpms\errorCodeService');
        $this->errorService = $errorCodeService;

        parent::setUp();
    }

    public function testSendPayloadPlugin(): void
    {
        $controllerPluginManager = $this->serviceManager->get('ControllerPluginManager');
        /** @var SendPayload $plugin */
        $plugin = $controllerPluginManager->get('sendPayload');
        $this->assertInstanceOf('CpmsCommon\Controller\Plugin\SendPayload', $plugin);
        /** @var \CpmsCommonTest\SampleController $controller */
        $controller = new SampleController();// $this->serviceManager->get('CpmsCommonTest\Controller\Sample');
        $event      = new MvcEvent();
        $event->setRequest(new Request());
        $controller->setEvent($event);
        $controller->setServiceLocator($this->serviceManager);
        $plugin->setController($controller);

        $payload = $this->errorService->getErrorMessage(ErrorCodeService::INVALID_CLIENT, [''], 500);

        $model = $plugin($payload);

        $this->assertInstanceOf('Laminas\View\Model\ViewModel', $model);
        $this->assertSame(ErrorCodeService::INVALID_CLIENT, $model->getVariable(ErrorCodeService::ERROR_CODE_KEY));
        $this->assertSame(500, $controller->getResponse()->getStatusCode());
    }
}
