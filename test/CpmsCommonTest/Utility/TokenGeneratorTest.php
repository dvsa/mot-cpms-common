<?php

namespace CpmsCommonTest\Utility;

use CpmsCommon\Utility\TokenGenerator;
use InvalidArgumentException;
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

    public function testGeneratesToken(): void
    {
        $token = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $this->assertTrue((bool)preg_match('/^' . TokenGenerator::TOKEN_REGEX . '$/', $token));

        $token = TokenGenerator::create(TokenGenerator::PREFIX);
        $this->assertTrue($this->tokenGenerator->verify($token));
    }

    public function testThrowsExceptionWithInvalidPrefix(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/CpmsCommon\\\\Utility\\\\TokenGenerator::generate\\(\\): Argument #1 \\(\\$prefix\\) must be of type int, string given/');

        /** @phpstan-ignore argument.type */
        TokenGenerator::create('badgers love mash potato');
    }

    public function testVerifyWithNotNumericPrefix(): void
    {
        $token        = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $invalidToken = str_replace(strval(TokenGenerator::PREFIX), 'a', $token);

        $this->assertFalse($this->tokenGenerator->verify($invalidToken));
    }

    public function testVerifyWithIncorrectLength(): void
    {
        $token        = $this->tokenGenerator->create(TokenGenerator::PREFIX);
        $invalidToken = substr($token, 0, 32);

        $this->assertFalse($this->tokenGenerator->verify($invalidToken));
    }
}
