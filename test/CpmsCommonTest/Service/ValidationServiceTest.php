<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\AbstractInputFilterFactory;
use CpmsCommon\Service\ErrorCodeService;
use CpmsCommon\Service\ValidationService;
use CpmsCommonTest\Bootstrap;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use Laminas\Mvc\Application;
use Laminas\ServiceManager\ServiceManager;

class ValidationServiceTest extends \PHPUnit\Framework\TestCase
{
    protected ValidationService $service;

    protected ServiceManager $serviceManager;

    protected string $mockFilter = 'CpmsCommonTest\inputFilter\mockFilter';

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        /** @var ValidationService $service */
        $service = $this->serviceManager->get('cpms\service\validationService');

        $this->service = $service;
        $this->service->setServiceLocator($this->serviceManager);
    }

    public function testValidatorInstance(): void
    {
        $service = $this->serviceManager->get('cpms\service\validationService');

        $this->assertInstanceOf('CpmsCommon\Service\ValidationService', $service);
    }

    public function testValid(): void
    {
        $key = 'test';
        $key2 = 'test2';

        $result = $this->service->validateData([$key => 'test', $key2 => 'a'], $this->mockFilter);

        $this->assertTrue($result);
    }

    public function testInvalidIsEmpty(): void
    {
        $key = 'test';
        $key2 = 'test2';

        /** @var array $result */
        $result = $this->service->validateData(
            [$key => '', $key2 => 'as'],
            $this->mockFilter,
            true
        );

        $this->assertEquals(3, count($result));
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('http_status_code', $result);
        $this->assertEquals(sprintf('Missing %s parameter', $key), $result['message']);
    }

    public function testInvalid(): void
    {
        $key = 'test';
        $key2 = 'test2';
        $key2Min = 2;

        /** @var array $result */
        $result = $this->service->validateData(
            [$key => 'test', $key2 => 'test'],
            $this->mockFilter,
            true
        );

        $this->assertEquals(3, count($result));
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('http_status_code', $result);
        $this->assertEquals(sprintf('The input is more than %s characters long', $key2Min), $result['message']);
    }

    public function testUnknownError(): void
    {
        $mockFilter = $this->getMockBuilder(\Laminas\InputFilter\InputFilter::class)
            ->onlyMethods(['getMessages', 'setData', 'isValid'])->getMock();

        $mockFilter->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue(array([])));

        $mockFilter->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->serviceManager
            ->setService('CpmsCommonTest\inputFilter\mockFilter', $mockFilter);

        $data = array(
            'collection_day' => 5,
            'product_reference' => 'test',
        );

        /** @var array $result */
        $result = $this->service->validateData($data, 'CpmsCommonTest\inputFilter\mockFilter');

        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertSame(ErrorCodeService::UNKNOWN_ERROR, $result['code']);
    }

    public function testVersionedFilterNameByDefaultConfig(): void
    {
        $serviceFactory = new AbstractInputFilterFactory();
        /** @var array $config */
        $config = $this->serviceManager->get('config');
        $configVersion = 47;
        $config['api-tools-versioning']['default_version'] = $configVersion;
        $this->serviceManager->setService('config', $config);
        $serviceName = 'payment\inputFilter\MockFilterProvider';

        $generatedClassName = $serviceFactory->getClassName($serviceName, $this->serviceManager);
        $expectedClassName = sprintf('Payment\%s%s\InputFilter\MockFilterProvider', AbstractInputFilterFactory::VERSION_PREFIX, $configVersion);
        $this->assertSame($expectedClassName, $generatedClassName, 'class name mismatch');
    }

    public function testVersionedFilterNameByDefaultConfigWithRouteMatch(): void
    {
        $serviceFactory = new AbstractInputFilterFactory();
        $expectedVersion = 42;
        $serviceName = 'payment\inputFilter\MockFilterProvider';
        $configVersion = 42;
        $config['api-tools-versioning']['default_version'] = $configVersion;
        $this->serviceManager->setService('config', $config);

        /** @var Application $application */
        $application = $this->serviceManager->get('Application');
        /** @var MvcEvent $event */
        $event = $application->getMvcEvent();
        $routeMatch = new RouteMatch([]);

        $event->setRouteMatch($routeMatch);

        $generatedClassName = $serviceFactory->getClassName($serviceName, $this->serviceManager);

        $expectedClassName = sprintf('Payment\%s%s\InputFilter\MockFilterProvider', AbstractInputFilterFactory::VERSION_PREFIX, $expectedVersion);
        $this->assertSame($expectedClassName, $generatedClassName, 'class name mismatch');
    }

    public function testVersionedFilterNameByRouteMatch(): void
    {
        $serviceFactory = new AbstractInputFilterFactory();
        $expectedVersion = 12;
        $serviceName = 'payment\inputFilter\MockFilterProvider';

        /** @var Application $application */
        $application = $this->serviceManager->get('Application');
        /** @var MvcEvent $event */
        $event = $application->getMvcEvent();
        $routeMatch = new RouteMatch([]);
        $routeMatch->setParam('version', $expectedVersion);

        $event->setRouteMatch($routeMatch);

        $generatedClassName = $serviceFactory->getClassName($serviceName, $this->serviceManager);

        $expectedClassName = sprintf('Payment\%s%s\InputFilter\MockFilterProvider', AbstractInputFilterFactory::VERSION_PREFIX, $expectedVersion);
        $this->assertSame($expectedClassName, $generatedClassName, 'class name mismatch');
    }
}
