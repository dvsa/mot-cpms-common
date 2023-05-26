<?php

namespace CpmsCommon\Validator;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\Exception;

/**
 * Class DateRange
 *
 * @package CpmsCommon\Validator
 */
class DateRange extends AbstractValidator
{
    const INVALID_DATE         = 'invalidDate';
    const NOT_AFTER            = 'notAfter';
    const NOT_AFTER_INCLUSIVE  = 'notAfterInclusive';
    const NOT_BEFORE           = 'notBefore';
    const NOT_BEFORE_INCLUSIVE = 'notBeforeInclusive';

    protected $messageTemplates
        = array(
            self::INVALID_DATE         => "The input does not appear to be a valid date",
            self::NOT_AFTER            => "Date must be after '%afterDate%'",
            self::NOT_AFTER_INCLUSIVE  => "Date must be on or after '%afterDate%'",
            self::NOT_BEFORE           => "Date must be before '%beforeDate%'",
            self::NOT_BEFORE_INCLUSIVE => "Date must be on or before '%beforeDate%'"
        );

    protected $messageVariables
        = array(
            'afterDate'  => 'after',
            'beforeDate' => 'before',
        );

    protected $after;
    protected $before;
    protected $format = 'Y-m-d H:i:s';
    protected $inclusive = false;

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     *
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        if (is_string($value) === false) {
            throw new \RuntimeException('Supplied value must be a string');
        }
        // attempt to convert to a DateTime value. Add the '!' so that everything goes to unix epoch by default
        $date = \DateTime::createFromFormat('!' . $this->getFormat(), $value);
        if ($date instanceof \DateTimeInterface === false) {
            $this->error(self::INVALID_DATE);

            return false;
        }

        if (!$this->getAfter() and !$this->getBefore()) {
            throw new \RuntimeException('At least one of before or after should be set.');
        }

        if ($this->isInclusive()) {
            $valid = $this->testWhenInclusive($date);
        } else {
            $valid = $this->testWhenNotInclusive($date);
        }

        return $valid;
    }

    /**
     * @param $date
     *
     * @return bool
     */
    private function testWhenInclusive($date)
    {
        $valid = true;
        if ($this->getAfter() and $this->getAfter() > $date) {
            $this->error(self::NOT_AFTER_INCLUSIVE);
            $valid = false;
        }
        if ($this->getBefore() and $this->getBefore() < $date) {
            $this->error(self::NOT_BEFORE_INCLUSIVE);
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $date
     *
     * @return bool
     */
    private function testWhenNotInclusive($date)
    {
        $valid = true;
        if ($this->getAfter() and $this->getAfter() >= $date) {
            $this->error(self::NOT_AFTER);
            $valid = false;
        }
        if ($this->getBefore() and $this->getBefore() <= $date) {
            $this->error(self::NOT_BEFORE);
            $valid = false;
        }

        return $valid;
    }

    /**
     * @return \DateTime
     */
    public function getAfter()
    {
        if (!$this->after) {
            return null;
        }

        return new \DateTime($this->after);
    }

    /**
     * @param string $after
     */
    public function setAfter($after)
    {
        try {
            $date = new \DateTime($after);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid after date provided.');
        }
        $this->after = $date->format($this->getFormat());
    }

    /**
     * @return \DateTime
     */
    public function getBefore()
    {
        if (!$this->before) {
            return null;
        }

        return new \DateTime($this->before);
    }

    /**
     * @param string $before
     */
    public function setBefore($before)
    {
        try {
            $date = new \DateTime($before);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid before date provided.');
        }
        $this->before = $date->format($this->getFormat());
    }

    /**
     * @return boolean
     */
    public function isInclusive()
    {
        return $this->inclusive;
    }

    /**
     * @param boolean $inclusive
     */
    public function setInclusive($inclusive)
    {
        $this->inclusive = (bool)$inclusive;
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
    public function setFormat($format)
    {
        $this->format = $format;
    }
}
