<?php

namespace CpmsCommon\Queue;

/**
 * Class QueueInterface
 *
 * @package CpmsCommon\Queue
 */
interface QueueInterface
{
    public function enqueue(JobInterface $job);

    public function enqueueAll(array $jobs);
}
