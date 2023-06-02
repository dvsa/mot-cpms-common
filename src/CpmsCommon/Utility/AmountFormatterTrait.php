<?php

namespace CpmsCommon\Utility;

/**
 * Trait AmountFormatterTrait
 */
trait AmountFormatterTrait
{
    public function formatPoundsToPence($amountInPounds)
    {
        return number_format($amountInPounds * 100, 0, '.', '');
    }
}
