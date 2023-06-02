<?php

namespace CpmsCommon\Queue;

use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class JobInterface
 *
 * @package CpmsCommon\Queue
 */
interface JobInterface
{
    public function handle(ServiceLocatorInterface $serviceLocator);
}
