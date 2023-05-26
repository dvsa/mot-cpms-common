<?php
/**
 * A trait that allows getting error messages
 * The class using this trait must also implement ServiceLocatorAwareInterface
 *
 * @package     olcscommon
 * @subpackage  utility
 * @author      Pele Odiase <pele.odiase@valtech.co.uk>
 */

namespace CpmsCommon\Utility;

use CpmsCommon\Service\ErrorCodeService;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class Error CodeAwareTrait
 * The class using this trait MUST implement the ServiceLocatorAware interface
 * @method ServiceManager getServiceLocator()
 *
 * @package CpmsCommon\Utility
 */
trait ErrorCodeAwareTrait
{
    /**
     * Return an array with error code and message
     *
     * @param        $errorCode
     * @param array  $replacement
     * @param null   $httpStatusCode
     * @param array  $data
     *
     * @return array
     */
    public function getErrorMessage($errorCode, $replacement = [], $httpStatusCode = null, $data = array())
    {
        /** @var ErrorCodeService $errorService */
        /** @var Request $request */
        $replacement    = (array)$replacement;
        $container = $this->getServiceLocator();
        $errorService   = $container->get('cpms/errorCodeService');

        $httpStatusCode = empty($httpStatusCode) ? Response::STATUS_CODE_400 : $httpStatusCode;
        $message        = $errorService->getErrorMessage($errorCode, $replacement, $httpStatusCode, $data);

        if ($this->getServiceLocator()->has('logger')) {
            $this->getServiceLocator()->get('logger')->debug(print_r($message, true));
            if (isset($message['code']) and $message['code'] == ErrorCodeService::GENERIC_ERROR_CODE) {
                $request = $this->getServiceLocator()->get('request');

                if ($request instanceof Request) {
                    $debugInfo               = [
                        'server'  => $request->getServer()->getArrayCopy(),
                        'request' => $request->toString(),
                    ];
                    $debugInfo['getParams']  = $request->getQuery()->getArrayCopy();
                    $debugInfo['postParams'] = $request->getPost()->getArrayCopy();
                    $this->getServiceLocator()->get('logger')->debug(print_r($debugInfo, true));
                }
            }
        }

        return $message;
    }

    /**
     * Get success message
     *
     * @param array $data
     *
     * @return array
     */
    public function getSuccessMessage(array $data = null)
    {
        /** @var ErrorCodeService $errorService */
        $errorService = $this->getServiceLocator()->get('cpms\errorCodeService');

        return $errorService->getSuccessMessage($data);
    }

    /**
     * Get error message
     *
     * @param        $code
     * @param string $replacement
     *
     * @return mixed
     */
    public function getMessage($code, $replacement = '')
    {
        /** @var ErrorCodeService $errorService */
        $errorService = $this->getServiceLocator()->get('cpms\errorCodeService');

        return $errorService->getMessage($code, $replacement);
    }
}
