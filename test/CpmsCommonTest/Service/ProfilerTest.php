<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\Service\ProfilingInitializer;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\Mock\SimpleServiceMock;
use Laminas\EventManager\EventManager;

class ProfilerTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        /** @var array */
        $config = $this->serviceManager->get('Config');
        $config['cpms_profiler_enabled'] = true;
        $this->serviceManager->setService('Config', $config);

        parent::setUp();
    }

    public function testPreEvent(): void
    {
        $initializer = new ProfilingInitializer();
        $service     = new SimpleServiceMock();

        $service->setEventManager(new EventManager());

        /** @phpstan-ignore method.void */
        $initializerResult = $initializer->initialize($service, $this->serviceManager);

        $service->fireEvent('pre');

        $this->assertEmpty($initializerResult);
    }

    public function testPostEvent(): void
    {
        $initializer = new ProfilingInitializer();
        $service     = new SimpleServiceMock();

        $service->setEventManager(new EventManager());

        /** @phpstan-ignore method.void */
        $initializerResult = $initializer->initialize($service, $this->serviceManager);

        $service->fireEvent('post');

        $this->assertEmpty($initializerResult);
    }

    public function testAmountFormatterTrait(): void
    {
        $pound   = 10;
        $service = new SimpleServiceMock();
        $amount  = $service->formatPoundsToPence($pound);
        $this->assertEquals($pound * 100, $amount);
    }
}
