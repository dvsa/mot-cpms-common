<?php

namespace CpmsCommon\Utility;

/**
 * Trait AmountFormatterTrait
 */
trait AmountFormatterTrait
{
    public function formatPoundsToPence(float $amountInPounds): string
    {
        return number_format($amountInPounds * 100, 0, '.', '');
    }
}
