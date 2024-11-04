<?php

namespace CpmsCommon\Service;

use CpmsCommon\Log\LogDataAwareInterface;
use CpmsCommon\Log\LogDataAwareTrait;
use Laminas\Log\Logger;

/**
 * Class Logger Service
 *
 * @package       CpmsCommon
 * @subpackage    Log
 * @author        Pele Odiase <pele.odiase@valtech.co.uk>
 */
class LoggerService extends Logger implements LogDataAwareInterface
{
    use LogDataAwareTrait;

    /**
     * @param \Exception $exception
     *
     * @return $this
     */
    public function logException(\Exception $exception)
    {
        $this->getLogData()->setEntryType('exception');
        $this->err($this->processException($exception, false));

        return $this;
    }

    /**
     * Format exception
     *
     * @param \Exception $exception
     * @param bool       $returnLog
     *
     * @return string
     */
    public function processException(\Exception $exception, $returnLog = true)
    {
        $log   = '';
        $index = 1;
        $trace = $exception->getTraceAsString();

        $this->getLogData()->setExceptionCode($exception->getCode());
        $this->getLogData()->setExceptionMessage($exception->getMessage());
        $this->getLogData()->setStackTrace($trace);
        $this->getLogData()->setExceptionType(get_class($exception));

        if ($returnLog) {
            do {
                $messages[] = $index++ . ": " . $exception->getMessage();
            } while ($exception = $exception->getPrevious());

            $log .= "Exception:\n" . implode("\n", $messages) . "\nTrace:\n" . $trace . "\n\n";
        }

        return $log;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addReplacement($key, $value)
    {
        $this->getLogData()->{$key} = $value;

        return $this;
    }
}
