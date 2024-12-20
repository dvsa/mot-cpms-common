<?php

namespace CpmsCommonTest\Service;

use CpmsCommonTest\Bootstrap;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class LoggerServiceTest
 *
 * @package CpmsCommonTest\Service
 */
class DvsaLogFormatterTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceManager $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();

        parent::setUp();
    }

    public function testFormatterInstance(): void
    {
        $formatter = $this->serviceManager->get('dvsa\formatter');
        $this->assertInstanceOf('CpmsCommon\Log\Formatter\DvsaLogFormatter', $formatter);
    }
}
