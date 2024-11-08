<?php

/**
 * An abstract controller that all ordinary CPMS controllers inherit from
 *
 * @package     olcscommon
 * @subpackage  controller
 * @author      Pelle Wessman <pelle.wessman@valtech.se>
 */

namespace CpmsCommon\Controller;

use CpmsCommon\Utility\ErrorCodeAwareTrait;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController as ZendActionController;

/**
 * Class AbstractActionController
 * Base Abstract class for action controllers
 * @method download($file, $maskedFile)
 *
 * @package CpmsCommon\Controller
 */
abstract class AbstractActionController extends ZendActionController
{
    use ErrorCodeAwareTrait;
    use LoggerAwareTrait;
}
