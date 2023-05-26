<?php
namespace CpmsCommonTest\Service;

use CpmsCommonTest\Bootstrap;

/**
 * Class ApiContentTypeTest
 *
 * @package CpmsCommonTest\Service
 */
class ApiContentTypeTest extends \PHPUnit\Framework\TestCase
{

    public function testContentTypeNoVersion()
    {
        $data = Bootstrap::getInstance()->getServiceManager()->get('cpms\api\contentType');
        $this->assertTrue(is_string($data));
    }

    public function testContentType()
    {
        $serviceManager = Bootstrap::getInstance()->getServiceManager();
        $serviceManager->setAllowOverride(true);
        $config                                     = $serviceManager->get('config');
        $config['api-tools-versioning']['default_version'] = 75;
        $serviceManager->setService('config', $config);

        $data = $serviceManager->get('cpms\api\contentType');
        $this->assertTrue(is_string($data));
    }
}
