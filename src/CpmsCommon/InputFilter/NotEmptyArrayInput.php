<?php

namespace CpmsCommon\InputFilter;

use Laminas\InputFilter\ArrayInput;

/**
 * Class NotEmptyArrayInput
 *
 * @package InputFilter
 */
class NotEmptyArrayInput extends ArrayInput
{
    public function isValid($context = null)
    {
        // if we actually have an empty array, but have said this can't be empty, it should not pass validation. In
        // order to make this happen, populate the value with a nested empty array so that validation chain gets called.
        if ($this->allowEmpty() === false and count($this->getValue()) == 0) {
            $this->setValue([[]]);
        }

        return parent::isValid($context);
    }
}
