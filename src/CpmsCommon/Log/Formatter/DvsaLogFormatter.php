<?php

namespace CpmsCommon\Log\Formatter;

use CpmsCommon\Log\LogDataAwareInterface;
use CpmsCommon\Log\LogDataAwareTrait;
use DateTime;
use Laminas\Log\Formatter\Simple;

/**
 * Class CustomFormatter
 *
 * @package CpmsCommon\Log\Formatter
 */
class DvsaLogFormatter extends Simple implements LogDataAwareInterface
{
    use LogDataAwareTrait;

    /**
     * @param null $dateTimeFormat
     */
    public function __construct($dateTimeFormat = null)
    {
        parent::__construct($this->getFormat(), $dateTimeFormat);
    }

    /**
     * @param array $event
     *
     * @return mixed|string
     */
    public function format($event)
    {
        $output  = parent::format($event);
        $logData = $this->getReplacementValues();

        foreach ($logData as $name => $value) {
            if ($value != null) {
                $output = str_replace("%$name%", $value, $output);
            }
        }
        //Reset log data to prevent reuse
        $this->getLogData()->resetData();

        return $output;
    }

    /**
     * Determine log format. Exceptions have a difference format
     *
     * @return string
     */
    private function getFormat()
    {
        return '%timestamp%||%priority%||%priorityName%||%entryType%||%userId%||%openAmToken%||%accessToken%||' .
        '%correlationId%||%classMethod%||%message%||%extra%||%exceptionType%||%exceptionCode%||%exceptionMessage%||' .
        '%stackTrace%';
    }

    /**
     * @internal param $priority
     * @internal param $message
     * @internal param $extra
     * @return array
     */
    private function getReplacementValues()
    {
        $dateObject                = new DateTime();
        $date                      = $dateObject->format(\DateTime::ATOM);
        $replacements              = $this->logData ? $this->logData->toArray() : [];
        $replacements['timestamp'] = $date;

        return $replacements;
    }
}
