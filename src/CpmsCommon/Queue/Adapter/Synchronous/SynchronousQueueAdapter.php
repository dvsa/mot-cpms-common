<?php

namespace CpmsCommon\Queue\Adapter\Synchronous;

use CpmsCommon\Queue\JobInterface;
use CpmsCommon\Queue\QueueInterface;
use CpmsCommon\Utility\LoggerAwareTrait;
use Interop\Container\ContainerInterface;

/**
 * Class SynchronousQueueAdapter
 *
 * @package CpmsCommon\Queue\Adapter\Memory
 */
class SynchronousQueueAdapter implements QueueInterface
{
    use LoggerAwareTrait;

    // This is an anti-pattern added here to make PoC zf2->zf3 migration happen. Sorry. This should be fixed in the future!
    private $serviceLocator;

    public function setServiceLocator($serviceLocator): SynchronousQueueAdapter
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param JobInterface $job
     */
    public function enqueue(JobInterface $job): void
    {
        $this->process($job);
    }

    public function enqueueAll(array $jobs): void
    {
        array_map([$this, 'enqueue'], $jobs);
    }

    /**
     * @param JobInterface $job
     *
     * @return bool
     */
    protected function process(JobInterface $job)
    {
        try {
            return $job->handle($this->serviceLocator);
        } catch (\Exception $e) {
            return $this->logException($e);
        }
    }
}
