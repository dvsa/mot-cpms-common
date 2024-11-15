<?php

namespace CpmsCommonTest\Utility;

use CpmsCommonTest\Bootstrap;
use Laminas\ServiceManager\ServiceManager;
use CpmsCommonTest\Mock\LoggerAwareTraitMock;

class LoggerAwareTraitTest extends \PHPUnit\Framework\TestCase
{
    private LoggerAwareTraitMock $trait;

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
        $this->trait = new LoggerAwareTraitMock();
        $this->trait->setServiceLocator($serviceManager);
    }

    private function getServiceLocator(): ServiceManager
    {
        return Bootstrap::getInstance()->getServiceManager();
    }
}
