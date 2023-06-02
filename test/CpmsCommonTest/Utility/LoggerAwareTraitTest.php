<?php

namespace CpmsCommonTest\Utility;

use CpmsCommonTest\Bootstrap;

class LoggerAwareTraitTest extends \PHPUnit\Framework\TestCase
{

    private $trait;

    public function testLog()
    {
        $this->setUpTrait($this->getServiceLocator());

        $this->trait->log('message');

        $this->assertInstanceOf('CpmsCommon\Service\LoggerService', $this->trait->getLogger());
    }

    public function testLogException()
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
    private function setUpTrait($serviceManager)
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

    private function getServiceLocator()
    {
        return Bootstrap::getInstance()->getServiceManager();
    }

}
