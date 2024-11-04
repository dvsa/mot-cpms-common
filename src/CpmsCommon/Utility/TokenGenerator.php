<?php

namespace CpmsCommon\Utility;

use InvalidArgumentException;

/**
 * Class TokenGenerator
 * Class to generate and validate cpms tokens
 *
 * @package CpmsCommon\Utility
 * @author  Phil Burnett <philburnett@valtech.co.uk>
 */
class TokenGenerator
{
    /**
     * Token prefixes for different types
     */
    public const PREFIX = 1;

    /**
     * The character that separates the version from the rest of the token
     */
    public const VERSION_SEPARATOR = 'k';

    /**
     * The length of each part that makes up the bulk of the token
     */
    public const PART_LENGTH = 3;

    /**
     * The amount of part that makes up the bulk of the token
     */
    public const PART_COUNT = 10;

    /**
     * A regex to match the structure of the token
     */
    public const TOKEN_REGEX = '\d{1}[a-z0-9]{32}';

    public function generate($prefix = self::PREFIX)
    {
        if (empty($prefix) || strlen($prefix) > 1) {
            throw new InvalidArgumentException('Invalid token prefix (' . $prefix . ')');
        }

        $token      = $prefix . self::VERSION_SEPARATOR;
        $checkDigit = 0;

        for ($i = 0; $i < self::PART_COUNT; $i++) {
            $value = rand(pow(36, self::PART_LENGTH - 1), pow(36, self::PART_LENGTH) - 1);
            $token .= base_convert($value, 10, 36);
            $checkDigit += $this->summarizeValue($value);
        }

        // A check digit
        $token .= $this->summarizeValue($checkDigit);

        return $token;
    }

    /**
     * Verify a token
     *
     * @param  string
     *
     * @return bool   True if token is valid
     */
    public function verify($token)
    {
        $parts = explode(self::VERSION_SEPARATOR, $token, 2);

        if (empty($parts[1]) || !is_numeric($parts[0]) || strlen($parts[0]) > 1) {
            return false;
        }

        $token  = $parts[1];
        $length = strlen($token);

        if ($length < self::PART_LENGTH + 1 || $length % self::PART_LENGTH !== 1) {
            return false;
        }

        $checkDigit = substr($token, -1);
        $token      = substr($token, 0, -1);

        $sum = 0;
        for ($i = 0; $i < $length - 1; $i += self::PART_LENGTH) {
            $value = base_convert(substr($token, $i, self::PART_LENGTH), 36, 10);
            $sum += $this->summarizeValue($value);
        }

        return ($checkDigit == $this->summarizeValue($sum));
    }

    /**
     * static method for convenience
     *
     * @param int $prefix
     *
     * @return string
     */
    public static function create($prefix = self::PREFIX)
    {
        $generator = new TokenGenerator();

        return $generator->generate($prefix);
    }

    /**
     * Adds each number in a value to create a check digit
     *
     * @param  int $value
     *
     * @return int
     */
    private function summarizeValue($value)
    {
        do {
            $sum = 0;
            $value .= '';
            $valueLength = strlen($value);
            for ($j = 0; $j < $valueLength; $j++) {
                $sum += $value[$j];
            }
            $value = $sum;
        } while ($sum > 9);

        return $sum;
    }
}
