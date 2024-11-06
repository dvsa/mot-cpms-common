<?php

namespace CpmsCommon\Validator;

/**
 * Class ChequeDate
 *
 * @package Validator
 */
class ChequeDate extends DateRange
{
    protected ?string $after = '-6 months';
    protected ?string $before = 'now';
    protected bool $inclusive = true;
}
