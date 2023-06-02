<?php

namespace CpmsCommonTest\Log\Writer;

use CpmsCommon\Log\Writer\StreamWriterFactory;
use CpmsCommon\Utility\Util;
use CpmsCommonTest\Bootstrap;

/**
 * Class StreamWriterFactoryTest
 *
 * @package CpmsCommonTest\Log\Writer
 */
class StreamWriterFactoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var StreamWriterFactory
     */
    private $writerFactory;

    public function setUp(): void
    {
        $this->writerFactory = new StreamWriterFactory();
    }

    public function testCreateService()
    {
        $serviceManager = $this->getServiceManager();
        $result         = $this->writerFactory->__invoke($serviceManager, null);

        $this->assertInstanceOf('Laminas\Log\Writer\Stream', $result);
    }

    public function testCreateServiceWithNoFileName()
    {
        $config['logger']['filename']  = null;
        $config['logger']['location']  = null;
        $config['logger']['mode']      = 0755;
        $config['logger']['priority']  = '';
        $config['logger']['separator'] = '';
        $serviceManager                = $this->getServiceManager($config);
        /**
         * @var \Laminas\Log\Writer\Stream $result
         */
        $result = $this->writerFactory->__invoke($serviceManager, null);
        $this->assertInstanceOf('Laminas\Log\Writer\Stream', $result);
    }

    public function testCreateLogDir()
    {
        $config['logger']['filename']    = null;
        $config['logger']['location']    = sys_get_temp_dir() . '/log';
        $config['logger']['mode']        = 0755;
        $config['logger']['priority']    = '';
        $config['logger']['separator']   = '';
        $config['logger']['replacement'] = '';
        $serviceManager                  = $this->getServiceManager($config);

        if (file_exists($config['logger']['location'])) {
            Util::deleteDir($config['logger']['location']);
        }
        /**
         * @var \Laminas\Log\Writer\Stream $result
         */
        $result = $this->writerFactory->__invoke($serviceManager, null);
        $this->assertInstanceOf('Laminas\Log\Writer\Stream', $result);
    }

    private function getServiceManager($config = null)
    {
        $serviceManager = Bootstrap::getInstance()->getServiceManager();

        if (!empty($config)) {

            $config = array_merge($serviceManager->get('config'), $config);

            $serviceManager->setAllowOverride(true);
            $serviceManager->setService('config', $config);

        }

        return $serviceManager;
    }
}
