<?php

namespace CpmsCommon\Log;

use CpmsCommon\Log\LogData;

/**
 * Interface LogDataAwareInterface
 *
 * @package CpmsCommon
 */
interface LogDataAwareInterface
{
    /**
     * @param LogData $logData
     *
     * @return mixed
     */
    public function setLogData($logData);

    /**
     * @return LogData
     */
    public function getLogData();
}
