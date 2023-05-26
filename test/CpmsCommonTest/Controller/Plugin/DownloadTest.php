<?php
namespace CpmsCommonTest\Controller\Plugin;

use CpmsCommon\Controller\Plugin\Download;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleController;
use Laminas\Mvc\Controller\ControllerManager;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class SampleControllerTest
 *
 * @package CpmsCommonTest\Controller
 */
class DownloadTest extends AbstractControllerTestCase
{
    private static $rootPath = __DIR__ . '/../../../../';

    /** @var SampleController */
    protected $controller;
    /** @var  Download */
    protected $plugin;

    public function setUp(): void
    {
        $this->setApplicationConfig(
            include self::$rootPath . 'config/application.config.php'
        );
        $serviceManager = Bootstrap::getInstance()->getServiceManager();

        $this->setApplicationConfig($serviceManager->get('ApplicationConfig'));
        $loader = $this->getApplicationServiceLocator()->get('ControllerManager');
        $this->controller = $loader->get('CpmsCommonTest\Sample');
        $this->controller->setServiceLocator($this->getApplicationServiceLocator());
        $serviceManager->setAllowOverride(true);
        parent::setUp();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('CpmsCommon\Controller\Plugin\Download', $this->controller->plugin(Download::class));
    }

    public function testDownload()
    {
        $testFile     = self::$rootPath . 'test/test.global.php';
        $maskFileName = 'same.txt';
        $response     = $this->controller->download($testFile, $maskFileName);

        $this->assertInstanceOf('Laminas\Http\Response\Stream', $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testDownloadNotExist()
    {
        $testFile     = self::$rootPath . 'test/non-test.global.php';
        $maskFileName = 'same.txt';
        $response     = $this->controller->download($testFile, $maskFileName);

        $this->assertInstanceOf('Laminas\Http\Response', $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDownloadWithoutMask()
    {
        $testFile     = self::$rootPath . 'test/test.global.php';
        $maskFileName = '';
        $response     = $this->controller->download($testFile, $maskFileName);

        $this->assertInstanceOf('Laminas\Http\Response\Stream', $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
