<?php

/**
 * An abstract controller that all ordinary CPMS controllers inherit from
 */

namespace CpmsCommon\Controller;

use CpmsCommon\Utility\ErrorCodeAwareTrait;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController as ZendActionController;
use CpmsCommon\Utility\LoggerAwareInterface;

/**
 * Class AbstractActionController
 * Base Abstract class for action controllers
 * @method download($file, $maskedFile)
 *
 * @package CpmsCommon\Controller
 */
abstract class AbstractActionController extends ZendActionController implements LoggerAwareInterface
{
    use ErrorCodeAwareTrait;
    use LoggerAwareTrait;
}
