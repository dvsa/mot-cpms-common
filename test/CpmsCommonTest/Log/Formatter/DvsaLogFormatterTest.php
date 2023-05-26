<?php
namespace CpmsCommonTest\Service;

use CpmsCommonTest\Bootstrap;

/**
 * Class LoggerServiceTest
 *
 * @package CpmsCommonTest\Service
 */
class DvsaLogFormatterTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp() :void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();

        parent::setUp();
    }

    public function testFormatterInstance()
    {
        $formatter = $this->serviceManager->get('dvsa\formatter');
        $this->assertInstanceOf('CpmsCommon\Log\Formatter\DvsaLogFormatter', $formatter);
    }
}
