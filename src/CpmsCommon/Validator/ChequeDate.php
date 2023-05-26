<?php

namespace CpmsCommon\Validator;

/**
 * Class ChequeDate
 *
 * @package Validator
 */
class ChequeDate extends DateRange
{
    protected $after = '-6 months';
    protected $before = 'now';
    protected $inclusive = true;
}
