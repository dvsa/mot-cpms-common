<?php

namespace CpmsCommonTest\Utility;

use CpmsCommon\Utility\TokenGenerator;
use InvalidArgumentException;
use PaymentDb\Entity\AbstractEntity;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenGeneratorTest
 *
 * @package CpmsCommonTest\Utility
 * @author  Phil Burnett <philburnett@valtech.co.uk>
 */
class TokenGeneratorTest extends TestCase
{
    /**
     * @var TokenGenerator
     */
    protected $tokenGenerator;

    public function setUp(): void
    {
        $this->tokenGenerator = new TokenGenerator();
    }

    public function testGeneratesToken()
    {
        $token = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $this->assertTrue((boolean)preg_match('/^' . TokenGenerator::TOKEN_REGEX . '$/', $token));

        $token = TokenGenerator::create(TokenGenerator::PREFIX);
        $this->assertTrue($this->tokenGenerator->verify($token));
    }

    public function testThrowsExceptionWithInvalidPrefix()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid token prefix');

        TokenGenerator::create('badgers love mash potato');
    }

    public function testVerifyWithNotNumericPrefix()
    {
        $token        = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $invalidToken = str_replace(TokenGenerator::PREFIX, 'a', $token);

        $this->assertFalse($this->tokenGenerator->verify($invalidToken));
    }

    public function testVerifyWithIncorrectLength()
    {
        $token        = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $invalidToken = substr($token, 0, 32);

        $this->assertFalse($this->tokenGenerator->verify($invalidToken));
    }
}
