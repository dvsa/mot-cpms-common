<?php

/**
 * A trait so that controllers can easily integrate logging.
 *
 * @package     cpmsommon
 * @subpackage  utility
 * @author      Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */

namespace CpmsCommon\Utility;

use CpmsCommon\Service\LoggerService;
use Laminas\Log\Logger;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class LoggerAwareTrait
 * The class using this trait must implement the ServiceLocatorAwareInterface
 * @method ServiceManager getServiceLocator()
 *
 * @package CpmsCommon\Utility
 */
trait LoggerAwareTrait
{
    protected ?LoggerService $logger = null;

    /**
     * Returns an instantiated instance of Zend Log.
     *
     * @return LoggerService
     * @throws \InvalidArgumentException
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            /** @var LoggerService $logger */
            $logger = $this->getServiceLocator()->get('Logger');
            $this->setLogger($logger);
        }

        /** @var LoggerService */
        return $this->logger;
    }

    /**
     * Set logger object
     *
     * @return mixed
     */
    public function setLogger(LoggerService $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Logs a message to the defined logger.
     *
     * @param string $message
     * @param int $priority
     * @param array $extra
     *
     * @return void
     */
    public function log($message, $priority = Logger::INFO, $extra = array())
    {
        $this->getLogger()->log($priority, $message, $extra);
    }

    /**
     * Logs an exception
     *
     * @param \Exception $exception
     *
     * @return LoggerService
     */
    public function logException($exception)
    {
        return $this->getLogger()->logException($exception);
    }
}
