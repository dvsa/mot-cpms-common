<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\Service\LoggerService;
use CpmsCommon\Service\LoggerServiceFactory;
use DvsaLogger\Contract\TokenServiceInterface;
use Laminas\ServiceManager\ServiceManager;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for include_token resolution in LoggerServiceFactory.
 *
 * The named logger 'mot-cpms-common' must honour, in order:
 *  1. mot_logger.loggers['mot-cpms-common'].include_token
 *  2. mot_logger.include_token
 *  3. default false
 *
 * @package CpmsCommonTest\Service
 */
final class LoggerServiceFactoryTest extends TestCase
{
    private const TOKEN_VALUE = 'test-token-value';

    public function testIncludeTokenDefaultsToFalse(): void
    {
        $container = $this->createContainer([
            'loggers' => [
                LoggerServiceFactory::LOGGER_NAME => [],
            ],
        ]);

        $record = $this->emitRecord($container);

        $this->assertArrayNotHasKey(
            'token',
            $this->getMetadata($record),
            'token must not be included when include_token is not configured anywhere',
        );
    }

    public function testGlobalIncludeTokenAppliesWhenNamedValueAbsent(): void
    {
        $container = $this->createContainer([
            'include_token' => true,
            'loggers' => [
                LoggerServiceFactory::LOGGER_NAME => [],
            ],
        ]);

        $record = $this->emitRecord($container);

        $this->assertSame(
            self::TOKEN_VALUE,
            $this->getMetadata($record)['token'] ?? null,
            'global mot_logger.include_token should apply when the named logger omits it',
        );
    }

    public function testNamedIncludeTokenFalseOverridesGlobalTrue(): void
    {
        $container = $this->createContainer([
            'include_token' => true,
            'loggers' => [
                LoggerServiceFactory::LOGGER_NAME => [
                    'include_token' => false,
                ],
            ],
        ]);

        $record = $this->emitRecord($container);

        $this->assertArrayNotHasKey(
            'token',
            $this->getMetadata($record),
            'explicit named include_token=false must take precedence over global true',
        );
    }

    public function testNamedIncludeTokenTrueOverridesGlobalFalse(): void
    {
        $container = $this->createContainer([
            'include_token' => false,
            'loggers' => [
                LoggerServiceFactory::LOGGER_NAME => [
                    'include_token' => true,
                ],
            ],
        ]);

        $record = $this->emitRecord($container);

        $this->assertSame(
            self::TOKEN_VALUE,
            $this->getMetadata($record)['token'] ?? null,
            'explicit named include_token=true must take precedence over global false',
        );
    }

    /**
     * Builds a container exposing the given mot_logger config plus a
     * TokenServiceInterface double returning a known token.
     *
     * Both 'config' (used by LoggerServiceFactory) and 'Config' (used by
     * MotLoggerFactory) keys are registered with the same array.
     *
     * @param array<string, mixed> $motLoggerConfig
     */
    private function createContainer(array $motLoggerConfig): ServiceManager
    {
        $config = [
            'mot_logger' => $motLoggerConfig,
        ];

        $tokenService = new class (self::TOKEN_VALUE) implements TokenServiceInterface {
            public function __construct(private readonly string $token)
            {
            }

            public function getToken(): string
            {
                return $this->token;
            }
        };

        $container = new ServiceManager();
        $container->setService('config', $config);
        $container->setService('Config', $config);
        $container->setService(TokenServiceInterface::class, $tokenService);

        return $container;
    }

    /**
     * Invokes the factory and captures the first record emitted by the
     * returned LoggerService.
     */
    private function emitRecord(ServiceManager $container): LogRecord
    {
        $service = (new LoggerServiceFactory())($container, 'Logger');
        $this->assertInstanceOf(LoggerService::class, $service);

        $handler = new TestHandler(Level::Debug);
        $service->getLogger()->pushHandler($handler);

        $service->error('include_token regression test');

        $records = $handler->getRecords();
        $this->assertNotEmpty($records, 'no record was emitted by the logger');

        return $records[0];
    }

    /**
     * @return array<string, mixed>
     */
    private function getMetadata(LogRecord $record): array
    {
        $extra = $record->extra;

        $this->assertIsArray($extra['__dvsa_metadata__'] ?? null, 'record is missing dvsa metadata');

        /** @var array<string, mixed> $metadata */
        $metadata = $extra['__dvsa_metadata__'];

        return $metadata;
    }
}
