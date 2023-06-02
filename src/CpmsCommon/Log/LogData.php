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
    /** @var  int */
    protected $exceptionCode;
    /** @var  string */
    protected $stackTrace;
    /** @var  string */
    protected $data;
    /** @var bool */

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
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
     */
    public function setEntryType($entryType)
    {
        $this->entryType = $entryType;
    }

    /**
     * @return int
     */
    public function getExceptionCode()
    {
        return $this->exceptionCode;
    }

    /**
     * @param int $exceptionCode
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
        $array = array();

        foreach ($this as $key => $value) {
            if ($key === '__strictMode__') {
                continue;
            }
            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @param boolean $_strictMode__
     */
    public function setStrictMode($_strictMode__)
    {
        $this->__strictMode__ = $_strictMode__;
    }

    /**
     *  Reset error specific data
     */
    public function resetData()
    {
        $this->setEntryType('');
        $this->setClassMethod('');
        $this->setEntryType('');
        $this->setExceptionMessage('');
        $this->setExceptionCode('');
        $this->setStackTrace('');
        $this->setData('');
        $this->setExceptionType('');
    }
}
