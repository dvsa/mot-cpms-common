<?php

namespace CpmsCommonTest\Mock;

use CpmsCommon\ControllerTrait\RedirectionDataTrait;

/**
 * Class RedirectionDataTraitMock
 *
 * @package CpmsCommonTest\Mock
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class RedirectionDataTraitMock
{
    use RedirectionDataTrait {
        handleRedirectionData as public;
    }

    public function sendPayload($data)
    {
        return $data;
    }
}
