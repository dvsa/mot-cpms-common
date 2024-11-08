<?php

namespace CpmsCommon\Log;

use Laminas\Stdlib\AbstractOptions;

/**
 * Class LogData
 *
 * @category Logger
 * @package  CpmsCommon\Log
 * @author   Pele Odiase <pele.odiase@valtech.co.uk>
 */
class LogData extends AbstractOptions
{
    /** @var  string */
    protected $entryType;
    /** @var  string */
    protected $userId;
    /** @var  string */
    protected $openAmToken;
    /** @var  string */
    protected $accessToken;
    /** @var  string */
    protected $correlationId;
    /** @var  string */
    protected $classMethod;
    /** @var  string */
    protected $exceptionType;
    /** @var  string */
    protected $exceptionMessage;
    /** @var  ?int */
    protected $exceptionCode = null;
    /** @var  string */
    protected $stackTrace;
    /** @var  string */
    protected $data;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
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
     * @return string
     */
    public function getClassMethod()
    {
        return $this->classMethod;
    }

    /**
     * @param string $classMethod
     *
     * @return void
     */
    public function setClassMethod($classMethod)
    {
        $this->classMethod = $classMethod;
    }

    /**
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    /**
     * @param string $correlationId
     *
     * @return void
     */
    public function setCorrelationId($correlationId)
    {
        $this->correlationId = $correlationId;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getEntryType()
    {
        return $this->entryType;
    }

    /**
     * @param string $entryType
     *
     * @return void
     */
    public function setEntryType($entryType)
    {
        $this->entryType = $entryType;
    }

    /**
     * @return ?int
     */
    public function getExceptionCode()
    {
        return $this->exceptionCode;
    }

    /**
     * @param ?int $exceptionCode
     *
     * @return void
     */
    public function setExceptionCode($exceptionCode)
    {
        $this->exceptionCode = $exceptionCode;
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }

    /**
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function setExceptionMessage($exceptionMessage)
    {
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * @return string
     */
    public function getExceptionType()
    {
        return $this->exceptionType;
    }

    /**
     * @param string $exceptionType
     *
     * @return void
     */
    public function setExceptionType($exceptionType)
    {
        $this->exceptionType = $exceptionType;
    }

    /**
     * @return string
     */
    public function getOpenAmToken()
    {
        return $this->openAmToken;
    }

    /**
     * @param string $openAmToken
     *
     * @return void
     */
    public function setOpenAmToken($openAmToken)
    {
        $this->openAmToken = $openAmToken;
    }

    /**
     * @return string
     */
    public function getStackTrace()
    {
        return $this->stackTrace;
    }

    /**
     * @param string $stackTrace
     *
     * @return void
     */
    public function setStackTrace($stackTrace)
    {
        $this->stackTrace = $stackTrace;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Cast to array
     *
     * @return array
     */
    public function toArray()
    {
        $properties = get_object_vars($this);
        unset($properties['__strictMode__']);

        return $properties;
    }

    /**
     * @param boolean $_strictMode__
     *
     * @return void
     */
    public function setStrictMode($_strictMode__)
    {
        $this->__strictMode__ = $_strictMode__;
    }

    /**
     *  Reset error specific data
     *
     * @return void
     */
    public function resetData()
    {
        $this->setEntryType('');
        $this->setClassMethod('');
        $this->setEntryType('');
        $this->setExceptionMessage('');
        $this->setExceptionCode(null);
        $this->setStackTrace('');
        $this->setData('');
        $this->setExceptionType('');
    }
}
