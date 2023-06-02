<?php

namespace CpmsCommonTest\View;

use CpmsCommon\Service\ErrorCodeService;
use CpmsCommon\View\JsonExceptionStrategy;
use Laminas\Http\Response;
use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;

/**
 * Class JsonExceptionStrategyTest
 *
 * @package CpmsCommonTest\View
 */
class JsonExceptionStrategyTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var JsonExceptionStrategy
     */
    private $strategy;

    public function setUp(): void
    {
        $this->strategy = new JsonExceptionStrategy();
        $this->strategy->setDisplayExceptions(true);
    }

    public function testPrepareExceptionViewModelWithNoError()
    {
        $event = $this->getEvent();
        $event->setError(null);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelWithResponseObj()
    {
        $event = $this->getEvent();
        $event->setResult(new Response());

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelControllerNotFound()
    {
        $event = $this->getEvent(Application::ERROR_CONTROLLER_NOT_FOUND);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelWithErrorException()
    {
        $event = $this->getEvent(Application::ERROR_EXCEPTION);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
        $this->assertInstanceOf('Laminas\View\Model\JsonModel', $event->getResult());
        $this->assertInstanceOf('Laminas\Http\Response', $event->getResponse());
        $this->assertEquals(500, $event->getResponse()->getStatusCode());
        $this->assertArrayHasKey('code', $event->getResult()->getVariables());
        $this->assertEquals(ErrorCodeService::CRITICAL_ERROR, $event->getResult()->getVariables()['code']);
    }

    public function testPrepareExceptionViewModelWithErrorExceptionAndResponse()
    {
        $event = $this->getEvent(Application::ERROR_EXCEPTION);

        $response = new Response();
        $response->setStatusCode(200);
        $event->setResponse($response);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
        $this->assertEquals(500, $event->getResponse()->getStatusCode());
    }

    private function getEvent($error = Application::ERROR_CONTROLLER_NOT_FOUND)
    {
        $event = new MvcEvent();
        $event
            ->setError($error)
            ->setParam('exception', new \Exception('Exception'));

        return $event;
    }
}
