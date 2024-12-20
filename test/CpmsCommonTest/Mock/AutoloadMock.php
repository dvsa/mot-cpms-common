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
     * @param string $class
     * @param string $directory
     *
     * @return string
     */
    public function testAutoLoad($class, $directory)
    {
        return $this->transformClassNameToFilename($class, $directory);
    }
}
