<?php

namespace CpmsCommonTest\Service\Config;

use CpmsCommon\Service\Config\AuthServiceOptions;
use CpmsCommon\Utility\PaymentScopeCodes;
use CpmsCommon\Utility\TokenGenerator;

/**
 * Class AuthenticationServiceOption
 *
 * @package CpmsCommonTest\Service\Config
 */
class AuthServiceOptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var AuthServiceOptions
     */
    private $options;

    public function setUp(): void
    {
        $this->options = new AuthServiceOptions();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSetsPropertiesCorrectly($data)
    {
        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            $this->options->$method($value);
        }

        foreach ($data as $property => $value) {
            $method = 'get' . ucfirst($property);
            $this->assertEquals($value, $this->options->$method());
        }

        $this->options->setDisabled(true);
        $this->assertTrue($this->options->isDisabled());

        $this->options->setEnforceToken(false);
        $this->assertFalse($this->options->isEnforceToken());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return
            [
                [
                    [
                        'accessToken'           => TokenGenerator::create(),
                        'scope'                 => 2345,
                        'requiredScope'         => PaymentScopeCodes::CARD,
                        'ipAddress'             => '127.0.0.1',
                        'ipWhiteList'           => array(),
                        'grantType'             => 'error',
                        'user'                  => 123,
                        'clientCode'            => 'mot',
                        'clientSecret'          => 999,
                        'method'                => 'get',
                        'disableAuthentication' => false,
                        'isDownload'            => false,
                    ]
                ]
            ];
    }
}
