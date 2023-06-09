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
    /** @var  NotEmptyArrayInput */
    protected $filter;

    public function setUp(): void
    {
        $this->filter = new NotEmptyArrayInput();
    }

    public function testRejectsAnEmptyArray()
    {
        $this->filter->setValue([]);
        $this->assertFalse($this->filter->isValid());
    }
}
