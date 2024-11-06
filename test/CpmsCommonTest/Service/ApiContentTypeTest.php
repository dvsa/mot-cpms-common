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
    public function testContentTypeNoVersion(): void
    {
        $data = Bootstrap::getInstance()->getServiceManager()->get('cpms\api\contentType');
        $this->assertTrue(is_string($data));
    }

    public function testContentType(): void
    {
        $serviceManager = Bootstrap::getInstance()->getServiceManager();
        $serviceManager->setAllowOverride(true);
        /** @var array */
        $config = $serviceManager->get('config');
        $config['api-tools-versioning']['default_version'] = 75;
        $serviceManager->setService('config', $config);

        $data = $serviceManager->get('cpms\api\contentType');
        $this->assertTrue(is_string($data));
    }
}
