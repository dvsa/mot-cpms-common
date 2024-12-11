<?php

namespace CpmsCommon\Queue;

/**
 * Class QueueInterface
 *
 * @package CpmsCommon\Queue
 */
interface QueueInterface
{
    public function enqueue(JobInterface $job): void;

    public function enqueueAll(array $jobs): void;
}
