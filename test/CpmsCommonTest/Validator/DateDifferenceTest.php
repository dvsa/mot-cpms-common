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

    public function testMaxDeltaGetAndSet(): void
    {
        $this->assertNull($this->validator->getMaxDelta());

        $this->validator->setMaxDelta('6 months');

        $expected = new \DateInterval('P6M');
        $actual = $this->validator->getMaxDelta();

        $this->assertEquals($expected->y, $actual->y);
        $this->assertEquals($expected->m, $actual->m);
        $this->assertEquals($expected->d, $actual->d);
        $this->assertEquals($expected->h, $actual->h);
        $this->assertEquals($expected->i, $actual->i);
        $this->assertEquals($expected->s, $actual->s);
        $this->assertEquals($expected->f, $actual->f);
        $this->assertEquals($expected->invert, $actual->invert);
    }

    /**
     * @param $date
     * @param $format
     *
     * @dataProvider invalidDateProvider
     */
    public function testRestrictsValueToProvidedFormat(mixed $date, string $format = null): void
    {
        $validator = $this->validator;

        if (!is_string($date)) {
            $this->expectException(\RuntimeException::class);
        }

        $validator->setFormat($format);

        $this->assertFalse($validator->isValid($date));
        $this->assertArrayHasKey(DateDifference::INVALID_DATE, $validator->getMessages());
    }

    public function invalidDateProvider(): array
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

    public function testValidationRequiresFieldToCompareWithToBePresent(): void
    {
        $valid = $this->validator->isValid('2015-11-11 12:00:00');

        $this->assertFalse($valid);
        $this->assertArrayHasKey(DateDifference::COMPARISON_KEY_NOT_FOUND, $this->validator->getMessages());

        $this->validator->setFieldToCompareWith('testField');

        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', []));
        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', ['not Correct' => 'not correct']));
    }

    public function testValidationRequiresFieldToCompareWithContainsValidDate(): void
    {
        $this->validator->setFieldToCompareWith('testField');
        $this->validator->setMaxDelta('1 month');

        $this->assertFalse($this->validator->isValid('2015-11-11 12:00:00', ['testField' => 'not correct']));
        $this->assertArrayHasKey(DateDifference::INVALID_COMP_DATE, $this->validator->getMessages());
        $this->assertTrue($this->validator->isValid('2015-11-11 12:00:00', ['testField' => '2015-11-10 12:00:00']));
    }

    /**
     * @dataProvider dataProvider
     * @param string $firstDate
     * @param string $secondDate
     * @param string $maxDelta
     * @param boolean $expected
     */
    public function testValidation($firstDate, $secondDate, $maxDelta, $expected): void
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

    public function dataProvider(): array
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
