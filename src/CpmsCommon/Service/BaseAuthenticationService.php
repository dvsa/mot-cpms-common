<?php

namespace CpmsCommon\Service;

use CpmsCommon\Service\Config\AuthServiceOptions;
use CpmsCommon\Utility\ErrorCodeAwareTrait;
use CpmsCommon\Utility\LoggerAwareTrait;
use Laminas\Authentication\AuthenticationService as ZendAuthService;

/**
 * Class BaseAuthenticationService
 *
 * @author       Pele Odiase <pele.odiase@valtech.co.uk>
 * @copyright    2014 ValTech
 */
abstract class BaseAuthenticationService extends ZendAuthService
{
    use ErrorCodeAwareTrait;
    use LoggerAwareTrait;

    protected AuthServiceOptions $options;

    /**
     * @param AuthServiceOptions $options
     *
     * @return void
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return AuthServiceOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
