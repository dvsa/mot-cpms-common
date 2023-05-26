<?php
namespace CpmsCommonTest;

use CpmsCommon\AbstractService;

/**
 * Class SampleService
 *
 * @package CpmsCommonTest
 */
class SampleService extends AbstractService
{

    public function getResult()
    {
        return $this->getPagedResultArray(array(), 1, 3, 5);
    }
}