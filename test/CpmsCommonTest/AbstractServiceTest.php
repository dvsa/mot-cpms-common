<?php

namespace CpmsCommonTest;

class AbstractServiceTest extends \PHPUnit\Framework\TestCase
{
    const MODEL_SERVICE        = 'cpms\model\temp';
    const ERROR_MESSAGE_RESULT = 'error';

    /**
     * @var \CpmsCommon\AbstractService
     */
    private $service;

    public function setUp() :void
    {
        $this->service = $this->getMockForAbstractClass(
            'CpmsCommon\AbstractService',
            [],
            '',
            true,
            true,
            true,
            ['getServiceLocator', 'getErrorMessage']
        );

        $serviceManager = Bootstrap::getInstance()->getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(self::MODEL_SERVICE, $this->getModelService());

        $this->service->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        /**
         * isolate error aware trait
         */
        $this->service->expects($this->any())
            ->method('getErrorMessage')
            ->will($this->returnValue(self::ERROR_MESSAGE_RESULT));
    }

    public function testGetModel()
    {
        $result = $this->service->getModel('temp');

        $this->assertEquals($this->getModelService(), $result);
    }

    public function testGetParamsWithRequired()
    {
        $required = [
            'param1'
        ];
        $data     = ['param1' => true];

        $result = $this->service->getParams($data, $required);

        $this->assertArrayHasKey('params', $result);
        $this->assertEquals($data, $result['params']);
    }

    public function testGetParamsWithNotRequired()
    {
        $required = [
            'param1',
            'param2'
        ];
        $data     = ['param1'];

        $result = $this->service->getParams($data, $required);
        $this->assertEquals(self::ERROR_MESSAGE_RESULT, $result);
    }

    /**
     * @dataProvider amountToValidate
     */
    public function testValidPositiveAmount($input, $output)
    {
        $result = $this->service->validPositiveAmount($input);
        $this->assertEquals($output, $result);
    }

    public function amountToValidate()
    {
        return [
            [1, true],
            [10, true],
            [30, true],
            [1000, true],
            [999999, true],
            [10101010, true],
            [0, false],
            [-2, false],
            [-1000, false],
            [-13131313131, false],
        ];
    }

    private function getModelService()
    {
        return new \stdClass();
    }
}
