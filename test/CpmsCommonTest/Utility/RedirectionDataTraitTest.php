<?php

namespace CpmsCommonTest\Utility;

use CpmsCommonTest\Mock\RedirectionDataTraitMock;

class RedirectionDataTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testTrait(): void
    {
        $mock = new RedirectionDataTraitMock();

        $data = [
            'gateway_url' => 'http://gateway.url.cpms',
            'key1'        => 'value1',
            'key2'        => 'value2',
            'key3'        => 'value3',
        ];

        $result = $mock->handleRedirectionData($data);

        $this->assertInstanceOf('Laminas\View\Model\ViewModel', $result);
    }

    public function testTraitWithNoGateway(): void
    {
        $mock = new RedirectionDataTraitMock();

        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $result = $mock->handleRedirectionData($data);

        $this->assertEquals($data, $result);
    }
}
