<?php

/**
 * An abstract controller that all ordinary CPMS controllers
 *
 * @package     olcscommon
 * @subpackage  controller
 * @author      Pele Odiase <pele.odiase@valtech.co.uk>
 */

namespace CpmsCommonTest\Controller;

use Laminas\Http\Header\ContentType;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Laminas\Mvc\Application;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as ZendTestCase;

/**
 * Class AbstractHttpControllerTestCase
 * Abstract test case for PhpUnit testing
 *
 * @package PaymentTest\Controller
 */
abstract class AbstractHttpControllerTestCase extends ZendTestCase
{
    protected array $clientMock = array();
    protected string $configDir = '/../../../../../config/test/application.config.php';

    public function setUp(bool $noConfig = false): void
    {
        if (!$noConfig) {
            $this->setApplicationConfig(include $this->configDir);
        }

        parent::setUp();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
    }

    /**
     * Dispatch the MVC with an URL and a body
     *
     * @param  string       $url
     * @param  string       $method      HTTP Method to use
     * @param  string|array $body        The body or, if JSON, an array to encode as JSON
     * @param  string       $contentType The Content-Type HTTP header to set
     *
     * @throws \Exception
     */
    public function dispatchBody($url, $method, $body, $contentType = 'application/json'): void
    {
        if (!is_string($body) && $contentType == 'application/json') {
            $body = json_encode($body);
        }

        $this->url($url, $method);

        /** @var Request $request */
        $request = $this->getRequest();
        $request->setContent($body);

        $headers = new Headers();
        $headers->addHeader(ContentType::fromString('Content-Type: ' . $contentType));
        $request->setHeaders($headers);

        $this->getApplication()->run();

        if (true !== $this->traceError) {
            return;
        }
        /** @var Application $app */
        $app = $this->getApplication();
        $exception = $app->getMvcEvent()->getParam('exception');
        if ($exception instanceof \Exception) {
            throw $exception;
        }
    }
}
