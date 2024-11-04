<?php

namespace CpmsCommonTest\Controller;

use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleController;
use Interop\Container\ContainerInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\PluginManager;
use Laminas\Router\RouteMatch;
use Laminas\View\Model\JsonModel;

class AbstractRestfulControllerTest extends \PHPUnit\Framework\TestCase
{
    const ERROR_MSG = 'error msg';

    /**
     * @var \CpmsCommon\Controller\AbstractRestfulController
     */
    private $controller;
    /** @var  ContainerInterface */
    protected $serviceManager;


    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->controller = new SampleController();
        $this->controller->setServiceLocator($this->serviceManager);
    }

    public function testDispatch()
    {
        $this->controller->setPluginManager(new PluginManager($this->serviceManager));
        $this->controller->getEvent()->setRouteMatch(
            new RouteMatch(
                [
                    'controller' => 'controller',
                    'action'     => 'index',
                ]
            )
        );

        $result = $this->controller->dispatch($this->getRequest(), $this->getResponse());

        $this->assertInstanceOf(JsonModel::class, $result);
    }

    public function testDispatchWith405StatusCode()
    {
        $this->controller->setPluginManager(new PluginManager($this->serviceManager));
        $this->controller->getEvent()->setRouteMatch(
            new RouteMatch(
                [
                    'controller' => 'CpmsCommonTest\Sample',
                    'id'         => 23
                ]
            )
        );

        $response = new \Laminas\Http\PhpEnvironment\Response();
        $response->setStatusCode(405);
        $request = $this->getRequest();
        $request->setMethod('DELETE');
        $result = $this->controller->dispatch($request, $response);

        $this->assertEquals($response->getStatusCode(), 405);
    }

    public function testDispatchWithCaughtException()
    {
        $result = $this->controller->dispatch($this->getRequest(), $this->getResponse());

        $this->assertInstanceOf('Laminas\Http\PhpEnvironment\Response', $result);
        $this->assertEquals(500, $result->getStatusCode());
        $content = json_decode($result->getContent(), true);
        $this->assertSame(108, $content['code']);
    }

    private function getRequest()
    {
        return new Request();
    }

    private function getResponse()
    {
        return new Response();
    }
}
