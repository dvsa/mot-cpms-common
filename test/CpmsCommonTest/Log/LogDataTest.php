<?php

namespace CpmsCommonTest\Log\Writer;

use CpmsCommon\Log\LogData;
use CpmsCommon\Utility\TokenGenerator;
use Laminas\Json\Exception\InvalidArgumentException;

/**
 * Class LogDataTest
 *
 * @package CpmsCommonTest\Log\Writer
 */
class LogDataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var LogData
     */
    private $logData;

    public function setUp(): void
    {
        $this->logData = new LogData();
    }

    /**
     * @dataProvider logDataProvider
     */
    public function testSetsPropertiesCorrectly($data)
    {
        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            $this->logData->$method($value);
        }

        foreach ($data as $property => $value) {
            $method = 'get' . ucfirst($property);
            $this->assertEquals($value, $this->logData->$method());
        }

        $this->logData->setFromArray($data);
        $arrayData = $this->logData->toArray();

        $this->assertTrue(is_array($arrayData));

        foreach ($data as $property => $value) {
            $this->assertSame($value, $arrayData[$property]);
        }
    }

    /**
     * @return array
     */
    public function logDataProvider()
    {
        return
            [
                [
                    [
                        'accessToken'      => TokenGenerator::create(),
                        'userId'           => 2345,
                        'classMethod'      => get_class($this),
                        'openAmToken'      => 'open-am-token',
                        'correlationId'    => 'no-application',
                        'entryType'        => 'error',
                        'exceptionType'    => get_class(new InvalidArgumentException()),
                        'exceptionMessage' => 'something went wrong here',
                        'exceptionCode'    => 999,
                        'stackTrace'       => 'where do i start?',
                        'data'             => 'all gone'
                    ]
                ]
            ];
    }
}
