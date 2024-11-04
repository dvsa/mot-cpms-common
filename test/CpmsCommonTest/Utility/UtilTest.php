<?php

namespace CpmsCommonTest\Utility;

use CpmsCommon\Utility\PaymentScopeCodes;
use CpmsCommon\Utility\Util;

class UtilTest extends \PHPUnit\Framework\TestCase
{
    public function testAppendQueryParam()
    {
        $url  = 'http://google.com';
        $url2 = 'http://google.com?home=1';
        $url3 = 'google.com?home=1';

        $time = time();

        $output = Util::appendQueryString($url, array('time' => $time));
        $this->assertSame($url . '?time=' . $time, $output);

        $output = Util::appendQueryString($url2, array('time' => $time));
        $this->assertSame($url2 . '&time=' . $time, $output);

        $output = Util::appendQueryString($url3, array('time' => $time));
        $this->assertSame($url2 . '&time=' . $time, $output);
    }

    public function testDeleteDir()
    {
        $dir = sys_get_temp_dir() . '/log';

        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        @touch($dir . '/test.log');

        $this->assertTrue(file_exists($dir));

        Util::deleteDir($dir);
        $this->assertFalse(file_exists($dir));
    }

    public function testGetReversalScopes()
    {
        $return = PaymentScopeCodes::getReversalPaymentScopeCodes();
        $this->assertTrue(is_array($return));
        $this->assertNotEmpty($return);
        $this->assertEquals(3, count($return));
    }
}
