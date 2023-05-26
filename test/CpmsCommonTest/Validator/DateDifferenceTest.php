<?php

namespace CpmsCommonTest\Validator;

use CpmsCommon\Validator\DateDifference;

/**
 * Class DateDifferenceTest
 * @package CpmsCommonTest\Validator
 */
class DateDifferenceTest extends \PHPUnit\Framework\TestCase
{
    /** @var  DateDifference */
    private $validator;

    public function setUp(): void
    {
        $this->validator = new DateDifference();
    }

    public function testMaxDeltaGetAndSet()
    {
        $this->assertNull($this->validator->getMaxDelta());

        $this->validator->setMaxDelta('6 months');

        $this->assertEquals(new \DateInterval('P6M'), $this->validator->getMaxDelta());
    }

    /**
     * @param $date
     * @param $format
     *
     * @dataProvider invalidDateProvider
     */
    public function testRestrictsValueToProvidedFormat($date, $format = null)
    {
        $validator = $this->validator;

        if (!is_string($date)) {
            $this->expectException(\RuntimeException::class);
        }

        $validator->setFormat($format);

        $this->assertFalse($validator->isValid($date));
        $this->assertArrayHasKey(DateDifference::INVALID_DATE, $validator->getMessages());
    }

    public function invalidDateProvider()
    {
        return [
            ['2012-111-01', 'Y-m-d'],
            ['2012-11-011', 'Y-m-d'],
            ['20122-11-011', 'Y-m-d'],
            ['not a date', 'Y-m-d'],
            [[]],
            [5],
            ['5'],
        ];
    }

    public function testValidationRequiresFieldToCompareWithToBePresent()
    {
        $valid = $this->validator->isValid('2015-11-11 12:00:00');

        $this->assertFalse($valid);
        $this->assertArrayHasKey(DateDifference::COMPARISON_KEY_NOT_FOUND, $this->validator->getMessages());

        $this->validator->setFieldToCompareWith('testField');

        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', []));
        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', ['not Correct' => 'not correct']));
    }

    public function testValidationRequiresFieldToCompareWithContainsValidDate()
    {
        $this->validator->setFieldToCompareWith('testField');
        $this->validator->setMaxDelta('1 month');

        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', ['testField' => 'not correct']));
        $this->assertArrayHasKey(DateDifference::INVALID_COMP_DATE, $this->validator->getMessages());
        $this->assertTrue($this->validator->isValid('2015-11-11 12:00:00', ['testField' => '2015-11-10 12:00:00']));
    }

    /**
     * @dataProvider dataProvider
     * @param $firstDate
     * @param $secondDate
     * @param $maxDelta
     * @param $expected
     */
    public function testValidation($firstDate, $secondDate, $maxDelta, $expected)
    {
        $validator = $this->validator;

        $validator->setMaxDelta($maxDelta);
        $validator->setFieldToCompareWith('secondDate');

        $isValid = $validator->isValid($firstDate, ['secondDate' => $secondDate]);

        $this->assertEquals($expected, $isValid);

        if ($expected === false) {
            $this->assertArrayHasKey(DateDifference::DIFFERENCE_TOO_LARGE, $validator->getMessages());
        }
    }

    public function dataProvider()
    {
        return [
            ['2015-06-30 12:00:00', '2015-01-01 12:00:00', '6 months', true ],
            ['2015-07-01 11:59:59', '2015-01-01 12:00:00', '6 months', true ],
            ['2015-07-01 12:00:00', '2015-01-01 12:00:00', '6 months', true ],
            ['2015-07-01 12:00:01', '2015-01-01 12:00:00', '6 months', false],
            ['2015-07-02 12:00:00', '2015-01-01 12:00:00', '6 months', false],

            ['2015-01-01 12:00:00', '2015-06-30 12:00:00', '6 months', true ],
            ['2015-01-01 12:00:00', '2015-07-01 11:59:59', '6 months', true ],
            ['2015-01-01 12:00:00', '2015-07-01 12:00:00', '6 months', true ],
            ['2015-01-01 12:00:00', '2015-07-01 12:00:01', '6 months', false],
            ['2015-01-01 12:00:00', '2015-07-02 12:00:00', '6 months', false],

            ['2015-01-05 12:00:00', '2015-07-06 11:59:59', '7 days',   false],
            ['2013-01-01 12:00:00', '2013-01-07 12:00:00', '7 days',   true ],
            ['2014-01-01 12:00:00', '2015-01-01 12:00:01', '7 days',   false],
            ['2015-01-07 12:00:00', '2015-01-14 12:00:00', '7 days',   true ],
            ['2015-01-09 12:00:01', '2015-01-02 12:00:00', '7 days',   false],
        ];
    }
}
