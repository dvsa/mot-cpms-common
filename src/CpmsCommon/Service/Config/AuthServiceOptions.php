<?php

namespace CpmsCommon\Service\Config;

use Laminas\Stdlib\AbstractOptions;

/**
 * Class AuthenticationServiceOptions
 *
 * @package Application\Service\Config
 */
class AuthServiceOptions extends AbstractOptions
{
    /**
     * OAuth 2 Scope
     * The scope provided by the client in the request
     */
    protected string $scope = '';

    /**
     * The scope required for the API endpoint
     */
    protected string|bool|null $requiredScope = null;

    /**
     * Scheme remote address
     */
    protected ?string $ipAddress = null;

    /**
     * IP address allowed to use this service
     */
    protected array $ipWhiteList = array();

    /**
     * Access token issued to client
     */
    protected string $accessToken = '';

    /**
     * OAuth 2.0 grant type
     */
    protected string $grantType = '';

    /**
     * Unique ID of user making the request
     */
    protected mixed $user;

    protected string $clientCode;

    protected string $clientSecret;

    protected bool $disabled = false;

    /**
     * Enforce the use of access token even in post request
     */
    protected bool $enforceToken = false;

    /**
     * The HTTP request method
     */
    protected string $method;

    protected bool $isDownload = false;

    /**
     * @return boolean
     */
    public function getIsDownload()
    {
        return $this->isDownload;
    }

    /**
     * @param boolean $isDownload
     *
     * @return void
     */
    public function setIsDownload($isDownload)
    {
        $this->isDownload = $isDownload;
    }

    /**
     * @return boolean
     */
    public function isEnforceToken()
    {
        return $this->enforceToken;
    }

    /**
     * @param boolean $enforceToken
     *
     * @return void
     */
    public function setEnforceToken($enforceToken)
    {
        $this->enforceToken = $enforceToken;
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param boolean $disabled
     *
     * @return void
     */
    public function setDisableAuthentication($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * @return boolean
     */
    public function getDisableAuthentication()
    {
        return $this->disabled;
    }

    /**
     * @param string $clientId
     *
     * @return void
     */
    public function setClientCode($clientId)
    {
        $this->clientCode = $clientId;
    }

    /**
     * @return string
     */
    public function getClientCode()
    {
        return $this->clientCode;
    }

    /**
     * @param string $clientSecret
     *
     * @return void
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param boolean $ignoreScope
     *
     * @return void
     */
    public function setRequiredScope($ignoreScope)
    {
        $this->requiredScope = $ignoreScope;
    }

    /**
     * @return string|bool|null
     */
    public function getRequiredScope()
    {
        return $this->requiredScope;
    }

    /**
     * @param mixed $userId
     *
     * @return void
     */
    public function setUser($userId)
    {
        $this->user = $userId;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $ipAddress
     *
     * @return void
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param array $ipWhiteList
     *
     * @return void
     */
    public function setIpWhiteList($ipWhiteList)
    {
        $this->ipWhiteList = $ipWhiteList;
    }

    /**
     * @return mixed
     */
    public function getIpWhiteList()
    {
        return $this->ipWhiteList;
    }

    /**
     * @param string $scope
     *
     * @return void
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $accessToken
     *
     * @return void
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $grantType
     *
     * @return void
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param boolean $disabled
     *
     * @return void
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }
}
