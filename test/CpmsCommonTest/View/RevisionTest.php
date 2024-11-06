<?php

namespace CpmsCommonTest\View;

use CpmsCommonTest\Bootstrap;
use CpmsCommon\View\Helper\Revision;

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
        /** @var array */
        $config = $this->serviceManager->get('config');
        $config['revision_file'] = __DIR__ . '/../revision-test.txt';
        $config['application_env'] = 'testing';
        $this->serviceManager->setService('config', $config);
    }

    public function testRevisionHelper(): void
    {
        $viewHelperManager = $this->serviceManager->get('ViewHelperManager');
        $helper = $viewHelperManager->get('displayRevision');
        $this->assertInstanceOf(Revision::class, $helper);
    }

    public function testRevisionLocal(): void
    {
        /** @var array */
        $config = $this->serviceManager->get('config');

        if (file_exists($config['revision_file'])) {
            unlink($config['revision_file']);
        }

        $viewHelperManager = $this->serviceManager->get('ViewHelperManager');
        /** @var Revision */
        $helper = $viewHelperManager->get('displayRevision');
        $helper->setServiceLocator($this->serviceManager);
        $data   = $helper();

        $this->assertNotEmpty($data);
        $this->assertTrue(is_string($data));
    }

    public function testRevisionWithFile(): void
    {
        $date    = date('r');
        $release = 'phpunit';
        /** @var array */
        $config  = $this->serviceManager->get('config');
        file_put_contents($config['revision_file'], $release . ';' . $date);

        $viewHelperManager = $this->serviceManager->get('ViewHelperManager');
        /** @var Revision */
        $helper = $viewHelperManager->get('displayRevision');
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
