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
     *
     * @var string
     */
    protected $scope = '';

    /**
     * The scope required for the API endpoint
     *
     * @var string
     */
    protected $requiredScope = null;
    /**
     * Scheme remote address
     *
     * @var string
     */
    protected $ipAddress = null;

    /**
     * IP address allowed to use this service
     *
     * @var array
     */
    protected $ipWhiteList = array();

    /**
     * Access token issued to client
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * OAuth 2.0 grant type
     *
     * @var string
     */
    protected $grantType = '';

    /**
     * Unique ID of user making the request
     *
     * @var
     */
    protected $user;

    /** @var  string */
    protected $clientCode;

    /** @var  string */
    protected $clientSecret;

    /** @var  bool */
    protected $disabled = false;

    /**
     * Enforce the use of access token even in post request
     *
     * @var bool
     */
    protected $enforceToken = false;

    /**
     * The HTTP request method
     *
     * @var string
     */
    protected $method;
    /**
     * @var bool
     */
    protected $isDownload = false;

    /**
     * @return boolean
     */
    public function getIsDownload()
    {
        return $this->isDownload;
    }

    /**
     * @param boolean $isDownload
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
     */
    public function setEnforceToken($enforceToken)
    {
        $this->enforceToken = $enforceToken;
    }

    /**
     * @param string $method
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
     */
    public function setRequiredScope($ignoreScope)
    {
        $this->requiredScope = $ignoreScope;
    }

    /**
     * @return string
     */
    public function getRequiredScope()
    {
        return $this->requiredScope;
    }

    /**
     * @param mixed $userId
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
     * @param mixed $ipAddress
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
     * @param mixed $ipWhiteList
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
     * @param mixed $scope
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
     * @param mixed $accessToken
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
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }
}
