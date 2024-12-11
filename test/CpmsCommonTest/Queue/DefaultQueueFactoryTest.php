<?php

namespace CpmsCommonTest\Queue;

use CpmsCommon\Queue\QueueInterface;
use PHPUnit\Framework\TestCase;
use CpmsCommon\Queue\DefaultQueueFactory;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class DefaultQueueFactoryTest
 * @package CpmsCommonTest\Queue
 */
class DefaultQueueFactoryTest extends TestCase
{
    public function testThrowsExceptionIfDefaultQueueAdapterNotSet(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Default queue adapter not configured');

        $factory = new DefaultQueueFactory();
        $serviceLocator = new ServiceManager();

        $serviceLocator->setService('config', ['queue_adapters' => ['test' => ['class' => 'StdClass']]]);

        $factory->__invoke($serviceLocator, null);
    }

    public function testThrowsExceptionIfClassIsNotDefinedForAdapter(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Class for default queue not set');

        $factory = new DefaultQueueFactory();
        $serviceLocator = new ServiceManager();

        $serviceLocator->setService('config', ['default_queue_adapter' => 'test', 'queue_adapters' => ['test']]);

        $factory->__invoke($serviceLocator, null);
    }

    public function testThrowsExceptionWhenConfiguredClassDoesNotSatisfyQueueInterface(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not load Queue adapter');

        $factory = new DefaultQueueFactory();
        $serviceLocator = new ServiceManager();
        $mockClass = new \StdClass();

        $serviceLocator->setService('test', $mockClass);
        $serviceLocator->setService(
            'config',
            ['default_queue_adapter' => 'test', 'queue_adapters' => ['test' => ['class' => 'test']]]
        );

        $factory->__invoke($serviceLocator, null);
    }

    public function testCanCreateInstance(): void
    {
        $factory = new DefaultQueueFactory();
        $serviceLocator = new ServiceManager();

        $mockClass = $this->getMockBuilder(QueueInterface::class)->getMock();
        $serviceLocator->setService('test', $mockClass);
        $serviceLocator->setService(
            'config',
            ['default_queue_adapter' => 'test', 'queue_adapters' => ['test' => ['class' => 'test']]]
        );

        $queue = $factory->__invoke($serviceLocator, null);

        $this->assertInstanceOf(QueueInterface::class, $queue);
    }
}
