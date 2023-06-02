<?php

namespace CpmsCommonTest\Utility;

use CpmsCommon\Service\ErrorCodeService;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\Mock\ErrorCodeAwareMock;

class ErrorCodeAwareTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetErrorMessageWithSampleCode()
    {
        $trait = $this->getMockBuilder(ErrorCodeAwareMock::class)->addMethods(['getServiceLocator'])->getMock();

        $trait->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue(Bootstrap::getInstance()->getServiceManager()));

        $result = $trait->getErrorMessage(ErrorCodeService::METHOD_NOT_ALLOWED);

        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals(ErrorCodeService::METHOD_NOT_ALLOWED, $result['code']);
    }
}
