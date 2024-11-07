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

    public function testPrepareExceptionViewModelWithNoError(): void
    {
        $event = $this->getEvent();
        /** @phpstan-ignore argument.type */
        $event->setError(null);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelWithResponseObj(): void
    {
        $event = $this->getEvent();
        $event->setResult(new Response());

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelControllerNotFound(): void
    {
        $event = $this->getEvent(Application::ERROR_CONTROLLER_NOT_FOUND);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
    }

    public function testPrepareExceptionViewModelWithErrorException(): void
    {
        $event = $this->getEvent(Application::ERROR_EXCEPTION);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
        $this->assertInstanceOf('Laminas\View\Model\JsonModel', $event->getResult());
        /** @var Response */
        $response = $event->getResponse();
        $this->assertInstanceOf('Laminas\Http\Response', $response);
        $this->assertEquals(500, $response->getStatusCode());

        /** @var array */
        $variables = $event->getResult()->getVariables();

        $this->assertArrayHasKey('code', $variables);
        $this->assertEquals(ErrorCodeService::CRITICAL_ERROR, $variables['code']);
    }

    public function testPrepareExceptionViewModelWithErrorExceptionAndResponse(): void
    {
        $event = $this->getEvent(Application::ERROR_EXCEPTION);

        $response = new Response();
        $response->setStatusCode(200);
        $event->setResponse($response);

        $result = $this->strategy->prepareExceptionViewModel($event);

        $this->assertNull($result);
        /** @var Response */
        $response = $event->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
    }

    private function getEvent(string $error = Application::ERROR_CONTROLLER_NOT_FOUND): MvcEvent
    {
        $event = new MvcEvent();
        $event
            ->setError($error)
            ->setParam('exception', new \Exception('Exception'));

        return $event;
    }
}
