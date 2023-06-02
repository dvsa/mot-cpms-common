<?php

namespace CpmsCommonTest\Mock;

use CpmsCommon\Utility\ApiAutoloader;

/**
 * Class AutoloadMock
 *
 * @package CpmsCommonTest\Mock
 */
class AutoloadMock extends ApiAutoloader
{
    /**
     * @param $class
     * @param $directory
     *
     * @return string
     */
    public function testAutoLoad($class, $directory)
    {
        return $this->transformClassNameToFilename($class, $directory);
    }
}
