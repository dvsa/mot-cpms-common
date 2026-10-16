<?php

namespace CpmsCommonTest\Config;

use PHPUnit\Framework\TestCase;

/**
 * Logger Configuration Tests
 *
 * Validates that the mot-logger v3.3.1 named logger configuration is properly
 * set up for the mot-cpms-common module.
 *
 * @package CpmsCommonTest\Config
 */
final class LoggerConfigTest extends TestCase
{
    /**
     * @var array<string, mixed> $config
     */
    private array $config = [];

    #[\Override]
    public function setUp(): void
    {
        $config = include __DIR__ . '/../../../config/module.config.php';
        if (!is_array($config)) {
            $this->fail('Module config file did not return an array');
        }
        /** @var array<string, mixed> $config */
        $this->config = $config;
    }

    public function testMotLoggerConfigExists(): void
    {
        $this->assertArrayHasKey('mot_logger', $this->config, 'mot_logger configuration not found');
        $this->assertIsArray($this->config['mot_logger']);
    }

    public function testNamedLoggerExists(): void
    {
        $loggers = $this->getLoggersConfig();

        $this->assertArrayHasKey(
            'mot-cpms-common',
            $loggers,
            'Named logger key mot-cpms-common not found in mot_logger.loggers config',
        );
        $this->assertArrayNotHasKey(
            'default',
            $loggers,
            'Named logger key must be mot-cpms-common, not default',
        );
    }

    public function testNamedLoggerChannel(): void
    {
        $namedConfig = $this->getNamedLoggerConfig();

        $this->assertArrayHasKey('channel', $namedConfig);
        $this->assertSame('cpms-common', $namedConfig['channel']);
    }

    public function testNamedLoggerWritersConfigured(): void
    {
        $namedConfig = $this->getNamedLoggerConfig();

        $this->assertArrayHasKey('writers', $namedConfig, 'writers key not found in named logger config');
        $this->assertIsArray($namedConfig['writers']);
        $this->assertNotEmpty($namedConfig['writers'], 'No writers configured for mot-cpms-common logger');
    }

    public function testFileWriterStructure(): void
    {
        $writer = $this->getWriter('file');

        $this->assertSame('stream', $writer['type'] ?? null, 'File writer type should be stream');
        $this->assertSame('json', $writer['formatter'] ?? null, 'File writer formatter should be json');
        $this->assertTrue($writer['enabled'] ?? false, 'File writer should be enabled');
        $this->assertNotEmpty($writer['level'] ?? null, 'File writer level should be set');
    }

    public function testFileWriterPathIsCorrectForThisRepository(): void
    {
        $writer = $this->getWriter('file');

        $this->assertArrayHasKey('path', $writer, 'File writer path not configured');
        $this->assertIsString($writer['path']);
        $this->assertStringContainsString('/var/log/dvsa/', $writer['path']);
        $this->assertMatchesRegularExpression(
            '/\d{4}-\d{2}-\d{2}-cpms-common\.log$/',
            $writer['path'],
            'Log file name should be {YYYY-MM-DD}-cpms-common.log',
        );
    }

    public function testStderrWriterStructure(): void
    {
        $writer = $this->getWriter('stderr');

        $this->assertSame('stream', $writer['type'] ?? null, 'Stderr writer type should be stream');
        $this->assertSame('php://stderr', $writer['path'] ?? null);
        $this->assertSame('pipe', $writer['formatter'] ?? null, 'Stderr writer formatter should be pipe');
        $this->assertTrue($writer['enabled'] ?? false, 'Stderr writer should be enabled');
    }

    /**
     * @return array<string, mixed>
     */
    private function getLoggersConfig(): array
    {
        $this->assertArrayHasKey('mot_logger', $this->config, 'mot_logger configuration not found');
        $this->assertIsArray($this->config['mot_logger']);
        $this->assertArrayHasKey('loggers', $this->config['mot_logger'], 'loggers key not found in mot_logger config');
        $this->assertIsArray($this->config['mot_logger']['loggers']);

        /** @var array<string, mixed> $loggers */
        $loggers = $this->config['mot_logger']['loggers'];

        return $loggers;
    }

    /**
     * @return array<string, mixed>
     */
    private function getNamedLoggerConfig(): array
    {
        $loggers = $this->getLoggersConfig();
        $this->assertArrayHasKey('mot-cpms-common', $loggers);
        $this->assertIsArray($loggers['mot-cpms-common']);

        /** @var array<string, mixed> $namedConfig */
        $namedConfig = $loggers['mot-cpms-common'];

        return $namedConfig;
    }

    /**
     * @return array<string, mixed>
     */
    private function getWriter(string $key): array
    {
        $namedConfig = $this->getNamedLoggerConfig();
        $this->assertArrayHasKey('writers', $namedConfig);
        $this->assertIsArray($namedConfig['writers']);
        $this->assertArrayHasKey($key, $namedConfig['writers'], "Writer '$key' not configured");
        $this->assertIsArray($namedConfig['writers'][$key]);

        /** @var array<string, mixed> $writer */
        $writer = $namedConfig['writers'][$key];

        return $writer;
    }
}
