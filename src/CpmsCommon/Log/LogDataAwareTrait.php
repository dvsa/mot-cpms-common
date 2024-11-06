<?php

namespace CpmsCommon\Log;

/**
 * Class LogDataAwareTrait
 *
 * @package CpmsCommon\Log
 */
trait LogDataAwareTrait
{
    /** @var ?LogData */
    protected $logData;

    /**
     * @return LogData
     */
    public function getLogData()
    {
        if (empty($this->logData)) {
            $this->logData = new LogData();
            $this->logData->setStrictMode(false);
        }

        return $this->logData;
    }

    /**
     * @param ?LogData $logData
     */
    public function setLogData($logData)
    {
        $this->logData = $logData;
    }
}
