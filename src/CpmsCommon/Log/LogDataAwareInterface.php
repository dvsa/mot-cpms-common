<?php
namespace CpmsCommon\Log;

/**
 * Interface LogDataAwareInterface
 *
 * @package CpmsCommon
 */
interface LogDataAwareInterface
{
    /**
     * @param $logData
     *
     * @return mixed
     */
    public function setLogData($logData);

    /**
     * @return LogData
     */
    public function getLogData();
}
