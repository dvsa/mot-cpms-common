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
use Laminas\Log\LoggerAwareTrait as LaminasLoggerAwareTrait;
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
    use LaminasLoggerAwareTrait;

    /**
     * Returns an instantiated instance of Zend Log.
     *
     * @return LoggerService
     * @throws \InvalidArgumentException
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            /** @var \Laminas\Log\Logger $logger */
            $logger = $this->getServiceLocator()->get('Logger');
            $this->setLogger($logger);
        }

        return $this->logger;
    }

    /**
     * Logs a message to the defined logger.
     *
     * @param       $message
     * @param int   $priority
     * @param array $extra
     */
    public function log($message, $priority = Logger::INFO, $extra = array())
    {
        $this->getLogger()->log($priority, $message, $extra);
    }

    /**
     * Logs an exception
     *
     * @param $exception
     *
     * @return $this
     */
    public function logException($exception)
    {
        return $this->getLogger()->logException($exception);
    }
}
