<?php

namespace CpmsCommonTest\View;

use CpmsCommon\View\Helper\Revision;
use CpmsCommonTest\Bootstrap;
use Laminas\Http\Response;

/**
 * Class RevisionTest
 *
 * @package CpmsCommonTest\View
 */
class RevisionTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);
        $config                  = $this->serviceManager->get('config');
        $config['revision_file'] = __DIR__ . '/../revision-test.txt';
        $config['application_env'] = 'testing';
        $this->serviceManager->setService('config', $config);
    }

    public function testRevisionHelper()
    {
        $helper = $this->serviceManager->get('ViewHelperManager')->get('displayRevision');
        $this->assertInstanceOf('CpmsCommon\View\Helper\Revision', $helper);
    }

    public function testRevisionLocal()
    {
        $config = $this->serviceManager->get('config');
        if (file_exists($config['revision_file'])) {
            unlink($config['revision_file']);
        }

        /** @var $helper Revision */
        $helper = $this->serviceManager->get('ViewHelperManager')->get('displayRevision');
        $helper->setServiceLocator($this->serviceManager);
        $data   = $helper();

        $this->assertNotEmpty($data);
        $this->assertTrue(is_string($data));
    }

    public function testRevisionWithFile()
    {
        $date    = date('r');
        $release = 'phpunit';
        $config  = $this->serviceManager->get('config');
        file_put_contents($config['revision_file'], $release . ';' . $date);

        /** @var $helper Revision */
        $helper = $this->serviceManager->get('ViewHelperManager')->get('displayRevision');
        $helper->setServiceLocator($this->serviceManager);
        $data   = $helper();

        $this->assertNotEmpty($data);
        $this->assertTrue(is_string($data));

        $dataView = $helper(true);
        $this->assertNotEmpty($dataView);
        $this->assertTrue(is_string($dataView));

        $this->assertNotSame($data, $dataView);
    }
}
