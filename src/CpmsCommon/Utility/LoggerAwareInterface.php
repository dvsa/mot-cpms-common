<?php

namespace CpmsCommon\Utility;

use CpmsCommon\Service\LoggerService;
use Laminas\Log\Logger;

interface LoggerAwareInterface
{
    public function getLogger(): LoggerService;

    public function setLogger(LoggerService $logger): self;

    public function log(string $message, int $priority = Logger::INFO, array $extra = array()): void;

    public function logException(\Exception $exception): LoggerService;
}
