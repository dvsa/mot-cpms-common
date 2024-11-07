<?php

namespace CpmsCommon\Validator;

use Exception as GlobalException;
use Laminas\Validator\AbstractValidator;
use Laminas\Validator\Exception;

/**
 * Class DateDifference
 *
 * @package Validator
 */
class DateDifference extends AbstractValidator
{
    public const COMPARISON_KEY_NOT_FOUND = 'comparisonKeyNotFound';
    public const INVALID_DATE             = 'invalidDate';
    public const INVALID_COMP_DATE        = 'invalidCompDate';
    public const DIFFERENCE_TOO_LARGE     = 'differenceTooLarge';

    protected array $messageTemplates = [
        self::INVALID_DATE             => "Date supplied is not valid. Expected format %format%, got %value%",
        self::DIFFERENCE_TOO_LARGE     => "Gap between dates is too large",
        self::COMPARISON_KEY_NOT_FOUND => "A second date must be specified for comparison",
        self::INVALID_COMP_DATE        => "Comparison date not valid",
    ];

    /**
     * @var \DateInterval The maximum length of time allowed between the two dates
     */
    protected $maxDelta;

    /**
     * @var string The name of the field/input/key of the second date for comparison, which will be passed into context
     */
    protected $fieldToCompareWith;

    /**
     * @var string Date format to check for when creating dates
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * @var array
     */
    protected $messageVariables = array(
        'format' => 'format'
    );

    /**
     * @return string
     */
    public function getFieldToCompareWith()
    {
        return $this->fieldToCompareWith;
    }

    /**
     * @param string $fieldToCompareWith
     */
    public function setFieldToCompareWith($fieldToCompareWith): void
    {
        $this->fieldToCompareWith = $fieldToCompareWith;
    }

    /**
     * Set the maximum length of time allowed between the two dates.
     *
     * @param string $spec A string using the relative formats, as used by the strtotime() function
     */
    public function setMaxDelta($spec): void
    {
        try {
            $date = \DateInterval::createFromDateString($spec);

            if ($date == false) {
                throw new \Exception('Invalid date string');
            }

            $this->maxDelta = $date;
        } catch (\Exception $exception) {
            throw new \Exception('Invalid date string');
        }
    }

    /**
     * @return \DateInterval
     */
    public function getMaxDelta()
    {
        return $this->maxDelta;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @param  array $context
     *
     * @return bool
     */
    public function isValid($value, $context = array())
    {
        $this->setValue($value);
        if (is_string($value) === false) {
            throw new \RuntimeException('Supplied value must be a string');
        }
        // attempt to convert to a DateTime value. Add the '!' so that everything goes to unix epoch by default
        $date = \DateTime::createFromFormat('!' . $this->getFormat(), $value);
        if ($date instanceof \DateTimeInterface === false) {
            $this->error(self::INVALID_DATE);

            return false;
        }

        if (empty($this->fieldToCompareWith) || !array_key_exists($this->getFieldToCompareWith(), $context)) {
            $this->error(self::COMPARISON_KEY_NOT_FOUND);

            return false;
        }

        $dateToCompareWith = \DateTime::createFromFormat(
            '!' . $this->getFormat(),
            $context[$this->getFieldToCompareWith()]
        );
        if ($dateToCompareWith instanceof \DateTimeInterface === false) {
            $this->error(self::INVALID_COMP_DATE);

            return false;
        }

        // in order to do the comparison, the larger and smaller date need to be known
        $laterDate   = max($date, $dateToCompareWith);
        $earlierDate = min($date, $dateToCompareWith);
        $maxDate     = clone $earlierDate;
        $maxDate->add($this->getMaxDelta());

        if ($laterDate > $maxDate) {
            $this->error(self::DIFFERENCE_TOO_LARGE);

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format): void
    {
        $this->format = $format;
    }
}
