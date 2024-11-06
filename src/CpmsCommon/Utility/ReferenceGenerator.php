<?php

namespace CpmsCommon\Utility;

use DateTime;
use InvalidArgumentException;

/**
 * Class ReferenceGenerator
 *
 * @package CpmsCommon\Utility
 */
class ReferenceGenerator
{
    /** Year reference for validation */
    public const YEAR_REFERENCE = 2014;

    /** Scheme identifier length */
    public const IDENTIFIER_LENGTH = 4;

    /** Payment type identifier length */
    public const PAYMENT_TYPE_LENGTH = 2;

    /** Unique identifier length */
    public const UNIQUE_ID_LENGTH = 8;
    /**
     * Segment separator
     */
    public const SEPARATOR = '-';

    /**
     * Regular expression to validate references
     */
    public const REFERENCE_REGEX = '[A-Z0-9]{4}\-[0-9]{2}\-[0-9]{8}\-[0-9]{6}\-[0-9A-Z]{8}';

    /**
     * Generate reference
     *
     * @param string $schemeId
     * @param string $paymentType
     *
     * @return string
     */
    public static function generate($schemeId, $paymentType)
    {
        if (!is_numeric($paymentType)) {
            throw new InvalidArgumentException("Payment type {$paymentType} must be a numeric value");
        }
        /**
         * Get the first 4 characters if longer
         */
        $schemeId = substr($schemeId, 0, self::IDENTIFIER_LENGTH);
        /**
         * Pad scheme ID with zeros if less than 4 characters
         */
        $schemeId = str_pad($schemeId, self::IDENTIFIER_LENGTH, '0', STR_PAD_RIGHT);

        /**
         * Get the first 2 numbers for payment type
         */
        $paymentType = substr($paymentType, 0, self::PAYMENT_TYPE_LENGTH);
        /**
         * Pad with zeros if less than 2
         */
        $paymentType = str_pad($paymentType, self::PAYMENT_TYPE_LENGTH, '0', STR_PAD_LEFT);

        /**
         * Get the current date in the right format
         */
        $dateObject = new DateTime('now');
        $date       = $dateObject->format('Ymd-His');
        /** unique identifier for the transaction $elapsed */
        $elapsed = hash('crc32', uniqid($schemeId . $paymentType) . microtime(true));

        /**
         * Pad to left to ensure that it has 8 digits
         */
        $elapsed = str_pad($elapsed, self::UNIQUE_ID_LENGTH, '0', STR_PAD_LEFT);

        /**
         * Glue pieces together
         */
        $reference = implode(self::SEPARATOR, array($schemeId, $paymentType, $date, $elapsed));

        //Return upper case reference
        return strtoupper($reference);
    }

    /**
     * Verify reference is in the correct format
     *
     * @param string $reference
     *
     * @return bool
     */
    public static function verify($reference)
    {
        $done = (bool)preg_match('#^' . self::REFERENCE_REGEX . '$#', $reference);

        if (empty($done)) {
            return false;
        }

        list(, , $date, $time) = explode(self::SEPARATOR, $reference);

        return (self::verifyDateSegment($date) and self::verifyTimeSegment($time));
    }

    /**
     * Verify time segment
     *
     * @param string $time
     *
     * @return bool
     */
    private static function verifyTimeSegment($time)
    {

        $hour    = (int)substr($time, 0, 2);
        $minute  = (int)substr($time, 2, 2);
        $seconds = (int)substr($time, -2);

        /**
         * Months should be between 1 and 12
         */
        if ($hour < 0 || $hour > 24) {
            return false;
        }

        /**
         * Days should be between 1 and 31
         */
        if ($minute < 0 || $minute > 60) {
            return false;
        }

        /**
         * Days cannot be more than 29 in February
         */
        if ($seconds < 0 || $seconds > 60) {
            return false;
        }

        return true;
    }

    /**
     * Verify date segment of the reference
     *
     * @param string $date
     *
     * @return bool
     */
    private static function verifyDateSegment($date)
    {
        $year  = (int)substr($date, 0, 4);
        $month = (int)substr($date, 4, 2);
        $day   = (int)substr($date, -2);

        /**
         * References could not have been created before 2014
         */
        if ($year < self::YEAR_REFERENCE) {
            return false;
        }

        /**
         * Months should be between 1 and 12
         */
        if ($month < 1 || $month > 12) {
            return false;
        }

        /**
         * Days should be between 1 and 31
         */
        if ($day < 0 || $day > 31) {
            return false;
        }

        /**
         * Days cannot be more than 29 in February
         */
        if ($month == 2 and $day > 29) {
            return false;
        }

        return true;
    }
}
