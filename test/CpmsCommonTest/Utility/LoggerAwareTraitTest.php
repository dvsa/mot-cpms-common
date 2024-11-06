<?php

namespace CpmsCommonTest\Utility;

use CpmsCommonTest\Bootstrap;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\MockObject\MockObject;

class LoggerAwareTraitTest extends \PHPUnit\Framework\TestCase
{
    private MockObject $trait;

    public function testLog(): void
    {
        $this->setUpTrait($this->getServiceLocator());

        $this->trait->log('message');

        $this->assertInstanceOf('CpmsCommon\Service\LoggerService', $this->trait->getLogger());
    }

    public function testLogException(): void
    {
        $this->setUpTrait($this->getServiceLocator());

        $result = $this->trait->logException(new \Exception('Exception'));

        $this->assertInstanceOf('CpmsCommon\Service\LoggerService', $result);
    }

    /**
     * Maybe isolate logger service?
     *
     * @param $serviceManager
     */
    private function setUpTrait(ServiceManager $serviceManager): void
    {
        $this->trait = $this->getMockForTrait(
            'CpmsCommon\Utility\LoggerAwareTrait',
            [],
            '',
            true,
            true,
            true,
            ['getServiceLocator']
        );

        $this->trait->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));
    }

    private function getServiceLocator(): ServiceManager
    {
        return Bootstrap::getInstance()->getServiceManager();
    }
}
