<?php

namespace CpmsCommonTest\Validator;

use CpmsCommon\Validator\DateRange;
use DateTime;

/**
 * Class DateRangeTest
 */
class DateRangeTest extends \PHPUnit\Framework\TestCase
{
    /** @var  DateRange */
    protected $validator;

    public function setUp(): void
    {
        $this->validator = new DateRange();
    }

    /**
     * @param $value
     *
     * @dataProvider validDateProvider
     * @throws \Exception
     */
    public function testSetBeforeAcceptsValidFormats($value)
    {
        $formattedDateString = $this->formatDateTimeWithoutMicroseconds($value);

        $this->validator->setBefore($value);

        $this->assertInstanceOf('\DateTimeInterface', $this->validator->getBefore());
        $this->assertEquals(new \DateTime($formattedDateString), $this->validator->getBefore());
    }

    /**
     * @dataProvider invalidDateProvider
     *
     * @param $value
     */
    public function testSetBeforeRejectsInvalidFormats($value)
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->validator->setBefore($value);

        $this->assertInstanceOf('\DateTimeInterface', $this->validator->getBefore());
    }

    /**
     * @param $value
     *
     * @dataProvider validDateProvider
     *
     * @throws \Exception
     */
    public function testSetAfterAcceptsValidFormats($value)
    {
        $formattedDateString = $this->formatDateTimeWithoutMicroseconds($value);

        $this->validator->setAfter($value);

        $this->assertInstanceOf('\DateTimeInterface', $this->validator->getAfter());
        $this->assertEquals(new \DateTime($formattedDateString), $this->validator->getAfter());
    }

    /**
     * @param $value
     *
     * @dataProvider invalidDateProvider
     *
     * @throws \Exception
     */
    public function testSetAfterRejectsInvalidFormats($value)
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->validator->setAfter($value);

        $this->assertInstanceOf('\DateTimeInterface', $this->validator->getAfter());
        $this->assertEquals(new DateTime($value), $this->validator->getAfter());
    }

    public function testValidationRequiresOneOfBeforeOrAfterToBeSet()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('At least one of before or after should be set.');

        $this->validator->isValid('2015-12-12 12:00:00');
    }

    public function testSetInclusiveDefaultIsFalse()
    {
        $this->assertFalse($this->validator->isInclusive());
    }

    public function testSetInclusive()
    {
        $this->validator->setInclusive(true);

        $this->assertTrue($this->validator->isInclusive());

        $this->validator->setInclusive(false);

        $this->assertFalse($this->validator->isInclusive());
    }

    /**
     * @param $date
     * @param $format
     *
     * @dataProvider invalidDateProvider
     */
    public function testRestrictsValueToProvidedFormat($date, $format = null)
    {
        if (!is_string($date)) {
            $this->expectException(\RuntimeException::class);
        }

        $this->validator->setFormat($format);

        $this->assertFalse($this->validator->isValid($date));

        $messages = $this->validator->getMessages();

        $this->assertEquals(array_pop($messages), 'The input does not appear to be a valid date');
    }

    /**
     * @param      $value
     * @param      $format
     * @param      $expectedResult
     * @param null $before
     * @param null $after
     * @param bool $inclusive
     *
     * @dataProvider dateRangeValueProvider
     */
    public function testValidationWorks(
        $value,
        $format,
        $expectedResult,
        $before = null,
        $after = null,
        $inclusive = false
    ) {
        if (!empty($before)) {
            $this->validator->setBefore($before);
        }
        if (!empty($after)) {
            $this->validator->setAfter($after);
        }
        $this->validator->setInclusive($inclusive);
        $this->validator->setFormat($format);

        $this->assertEquals(
            $expectedResult,
            $this->validator->isValid($value),
            implode(', ', $this->validator->getMessages())
        );
    }

    public function validDateProvider()
    {
        return [
            ['2012-11-01', 'Y-m-d'],
            ['2012-JAN-01', 'Y-M-d'],
            ['01-JAN-2015', 'dMY'],
            ['-6 months']
        ];
    }

    public function invalidDateProvider()
    {
        return [
            ['2012-1111-01', 'Y-m-d'],
            ['2012-11-011', 'Y-m-d'],
            ['20122-11-011', 'Y-m-d'],
            ['not a date', 'Y-m-d'],
            [5],
            ['5'],
        ];
    }

    public function dateRangeValueProvider()
    {
        $today = date('d-M-Y');

        return [
            // $value,      $format, $expected, $before,        $after,        $inclusive
            ['05-JAN-2015', 'd-M-Y', true, '06-JAN-2015'],                             // basic before
            ['05-JAN-2015', 'd-M-Y', false, '05-JAN-2015', null, false],      // basic before fail
            ['05-JAN-2015', 'd-M-Y', true, '05-JAN-2015', null, true], // basic before with inclusive
            ['05-JAN-2015', 'd-M-Y', true, null, '04-JAN-2015'],             // basic after
            ['05-JAN-2015', 'd-M-Y', false, null, '05-JAN-2015', false],      // basic after fail
            ['05-JAN-2015', 'd-M-Y', true, null, '05-JAN-2015', true],       // basic after inclusive
            ['05-JAN-2015', 'd-M-Y', true, '06-JAN-2015', '04-JAN-2015', false],      // basic between
            ['05-JAN-2015', 'd-M-Y', false, '05-JAN-2015', '04-JAN-2015', false],      // basic between
            ['05-JAN-2015', 'd-M-Y', false, '06-JAN-2015', '05-JAN-2015', false],      // basic between
            ['05-JAN-2015', 'd-M-Y', true, '06-JAN-2015', '05-JAN-2015', true],       // basic between
            ['05-JAN-2015', 'd-M-Y', false, '04-JAN-2015', '05-JAN-2015', true],       // basic between
            ['05-JAN-2015', 'd-M-Y', false, '04-JAN-2015', '06-JAN-2015', true],       // basic between
            ['05-JAN-2015', 'd-M-Y', true, '05-JAN-2015', '04-JAN-2015', true],       // basic between

            // checks not affected by alphabet sorting (aug < sep should fail)
            ['15-AUG-2015', 'd-M-Y', true, '15-SEP-2015'],
            ['15-AUG-2015', 'd-M-Y', true, '14-SEP-2015'],
            ['15-AUG-2015', 'd-M-Y', true, '16-SEP-2015'],
            ['15-AUG-2015', 'd-M-Y', false, '15-Jul-2015'],

            // relative dates
            [$today, 'd-M-Y', false, '-1 day'],
            [$today, 'd-M-Y', true, '+1 day'],
        ];
    }

    private function formatDateTimeWithoutMicroseconds($value)
    {
        $format = 'Y-m-d H:i:s';
        $expected = new \DateTime($value);
        return $expected->format($format);
    }
}
