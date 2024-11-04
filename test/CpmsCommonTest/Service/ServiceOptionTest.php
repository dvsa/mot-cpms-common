<?php

namespace CpmsCommonTest\Service;

use CpmsCommon\AbstractService;
use CpmsCommon\Service\Config\ServiceOptions;
use CpmsCommonTest\Bootstrap;
use CpmsCommonTest\SampleService;
use Laminas\Filter\Word\UnderscoreToCamelCase;

class ServiceOptionTest extends \PHPUnit\Framework\TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();

        parent::setUp();
    }

    public function testOptions()
    {
        $filter = new UnderscoreToCamelCase();

        $data = array(
            'sort'            => array('id' => 'asc'),
            'page'            => 1,
            'limit'           => 23,
            'depth'           => -1,
            'params'          => array(
                'required_fields' => array('id'),
                'depth'           => -1,
            ),
            'required_fields' => array('id'),
            'filters'         => array()
        );

        $options = new ServiceOptions($data);
        $options->setStrictMode(false);

        foreach ($data as $key => $value) {
            $method = 'get' . $filter->filter($key);
            $check  = $options->$method();
            $this->assertSame($value, $check);
        }

        $options->setLimit(100);
        $this->assertSame(ServiceOptions::MAX_LIMIT, $options->getLimit());

        $data    = array(
            'sort' => 'id:desc',
        );
        $options = new ServiceOptions($data);
        $this->assertSame('DESC', $options->getSort()['id']);
    }

    public function testResultArray()
    {
        $service = new SampleService();
        $result  = $service->getResult();

        $this->assertArrayHasKey(AbstractService::RESULT_ITEMS, $result);
        $this->assertArrayHasKey(AbstractService::RESULT_PAGE, $result);
        $this->assertArrayHasKey(AbstractService::RESULT_TOTAL, $result);
        $this->assertArrayHasKey(AbstractService::RESULT_LIMIT, $result);
    }
}
