<?php

namespace CpmsCommonTest\InputFilter;

use CpmsCommon\InputFilter\NotEmptyArrayInput;

/**
 * Class NotEmptyArrayInputTest
 *
 * @package CpmsCommonTest\InputFilter
 */
class NotEmptyArrayInputTest extends \PHPUnit\Framework\TestCase
{
    protected NotEmptyArrayInput $filter;

    public function setUp(): void
    {
        $this->filter = new NotEmptyArrayInput();
    }

    public function testRejectsAnEmptyArray(): void
    {
        $this->filter->setValue([]);
        $this->assertFalse($this->filter->isValid());
    }
}
