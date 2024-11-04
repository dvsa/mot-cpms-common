<?php

namespace CpmsCommonTest\Utility;

use CpmsCommonTest\Mock\AutoloadMock;

/**
 * Class AutoloadTest
 *
 * @package CpmsCommonTest\Utility
 */
class AutoloadTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test api auto loader class
     */
    public function testAppendQueryParam()
    {
        $class  = __NAMESPACE__ . '\SampleClass';
        $loader = new AutoloadMock();

        $this->assertInstanceOf('CpmsCommon\Utility\ApiAutoloader', $loader);
        $result = $loader->testAutoLoad($class, '');

        $this->assertSame('CpmsCommonTest/Utility/SampleClass.php', $result);
    }
}
