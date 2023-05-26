<?php
namespace CpmsCommonTest\Service;

use CpmsCommon\Service\ErrorCodeService;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleController;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\MvcEvent;

class ControllerPluginTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    /** @var \CpmsCommon\Service\ErrorCodeService $errorService */
    private $errorService;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();

        $this->errorService = $this->serviceManager->get('cpms\errorCodeService');

        parent::setUp();
    }

    public function testSendPayloadPlugin()
    {
        /** @var  \CpmsCommon\Controller\Plugin\SendPayload $plugin */
        $plugin = $this->serviceManager->get('ControllerPluginManager')->get('sendPayload');
        $this->assertInstanceOf('CpmsCommon\Controller\Plugin\SendPayload', $plugin);
        /** @var \CpmsCommonTest\SampleController $controller */
        $controller = new SampleController();// $this->serviceManager->get('CpmsCommonTest\Controller\Sample');
        $event      = new MvcEvent();
        $event->setRequest(new Request());
        $controller->setEvent($event);
        $controller->setServiceLocator($this->serviceManager);
        $plugin->setController($controller);

        $payload = $this->errorService->getErrorMessage(ErrorCodeService::INVALID_CLIENT, '', 500);
        /** @var  \Laminas\View\Model\ViewModel $model */
        $model = $plugin($payload);

        $this->assertInstanceOf('Laminas\View\Model\ViewModel', $model);
        $this->assertSame(ErrorCodeService::INVALID_CLIENT, $model->getVariable(ErrorCodeService::ERROR_CODE_KEY));
        $this->assertSame(500, $controller->getResponse()->getStatusCode());
    }
}