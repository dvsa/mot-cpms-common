<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\Service\ErrorCodeService;
use CpmsCommonTest\Bootstrap;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class ErrorCodeServiceTest
 *
 * @package CpmsCommonTest\Service
 */
class ErrorCodeServiceTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceManager $serviceManager;

    private ErrorCodeService $errorService;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        /** @var ErrorCodeService $errorService */
        $errorService = $this->serviceManager->get('cpms\errorCodeService');
        $this->errorService = $errorService;

        parent::setUp();
    }

    public function testCustomMessage(): void
    {
        $code    = 8765;
        $message = 'my message';

        $customMessages = [
            $code => $message
        ];

        $errorService = new ErrorCodeService($customMessages);
        $actual = $errorService->getErrorMessage($code);

        $this->assertArrayHasKey(ErrorCodeService::ERROR_CODE_KEY, $actual);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_MESSAGE_KEY, $actual);

        $this->assertSame($code, $actual[ErrorCodeService::ERROR_CODE_KEY]);
        $this->assertSame($message, $actual[ErrorCodeService::ERROR_MESSAGE_KEY]);
    }

    public function testGetErrorMessage(): void
    {
        $replacement = 'test field';
        $code = ErrorCodeService::CUSTOMER_NOT_FOUND;
        $actual = $this->errorService->getErrorMessage($code, [$replacement]);

        $this->assertArrayHasKey(ErrorCodeService::ERROR_CODE_KEY, $actual);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_MESSAGE_KEY, $actual);

        $this->assertSame($code, $actual[ErrorCodeService::ERROR_CODE_KEY]);
        $this->assertNotEmpty($actual[ErrorCodeService::ERROR_MESSAGE_KEY]);

        $actualWithStatus = $this->errorService->getErrorMessage(ErrorCodeService::INVALID_CLIENT, [$replacement], 500);
        $this->assertArrayHasKey(ErrorCodeService::HTTP_STATUS_KEY, $actualWithStatus);
    }

    public function testInvalidErrorCode(): void
    {
        /** @phpstan-ignore argument.type */
        $message = $this->errorService->getErrorMessage('xx90sedf');
        $this->assertSame(ErrorCodeService::GENERIC_ERROR_CODE, $message[ErrorCodeService::ERROR_CODE_KEY]);
    }

    public function testGetSuccessMessage(): void
    {
        $data = array(
            'token' => '223'
        );
        $message = $this->errorService->getSuccessMessage($data);
        $this->assertNotEmpty($message);

        $this->assertArrayHasKey(ErrorCodeService::ERROR_CODE_KEY, $message);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_MESSAGE_KEY, $message);

        $this->assertSame(ErrorCodeService::SUCCESS_MESSAGE, $message[ErrorCodeService::ERROR_CODE_KEY]);
        $this->assertSame($data['token'], $message['token']);

        $nullData = $this->errorService->getSuccessMessage();
        $this->assertArrayHasKey(ErrorCodeService::ERROR_CODE_KEY, $nullData);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_MESSAGE_KEY, $nullData);

        $this->assertSame(ErrorCodeService::SUCCESS_MESSAGE, $nullData[ErrorCodeService::ERROR_CODE_KEY]);
    }

    public function testGetSuccessMessageWithMessage(): void
    {
        $message = [
            ErrorCodeService::ERROR_CODE_KEY => ErrorCodeService::ACCESS_TOKEN_EXPIRED
        ];

        $data = $this->errorService->getSuccessMessage($message);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_CODE_KEY, $data);
        $this->assertArrayHasKey(ErrorCodeService::ERROR_MESSAGE_KEY, $data);

        $this->assertSame(ErrorCodeService::ACCESS_TOKEN_EXPIRED, $data[ErrorCodeService::ERROR_CODE_KEY]);
    }

    public function testGetFirstException(): void
    {
        $firstMessage  = 'First Message';
        $secondMessage = 'Second Message';
        $exception = new \InvalidArgumentException($firstMessage, 101);
        $output = ErrorCodeService::getFirstException($exception);
        $this->assertSame($firstMessage, $output->getMessage());

        $exception2 = new \InvalidArgumentException($secondMessage, 102, $exception);
        $output = ErrorCodeService::getFirstException($exception2);
        $this->assertSame($firstMessage, $output->getMessage());
    }

    public function testGetMessage(): void
    {
        $message = $this->errorService->getMessage(ErrorCodeService::AN_ERROR_OCCURRED);
        $this->assertNotEmpty($message);
    }

    public function testStaticGetMessage(): void
    {
        $message = ErrorCodeService::getMessage(ErrorCodeService::RESOURCE_NOT_FOUND);
        $this->assertNotEmpty($message);
    }
}
