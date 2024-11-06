<?php

namespace CpmsCommonTest\Utility;

use CpmsCommon\Utility\PaymentScopeCodes;
use CpmsCommon\Utility\ReferenceGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class ReferenceGeneratorTest
 *
 * @package CpmsCommonTest\Utility
 */
class ReferenceGeneratorTest extends TestCase
{
    /**
     * @var ReferenceGenerator
     */
    protected $referenceGenerator;

    public function setUp(): void
    {
        $this->referenceGenerator = new ReferenceGenerator();
    }

    public function testGenerateReference(): string
    {
        $reference = $this->referenceGenerator->generate('mot2', 2);

        $valid     = (bool)preg_match('/' . ReferenceGenerator::REFERENCE_REGEX . '/', $reference);

        $this->assertTrue($valid);

        return $reference;
    }

    /**
     * @depends testGenerateReference
     */
    public function testVerifyReference(string $reference): void
    {
        $this->assertTrue($this->referenceGenerator->verify($reference));
    }

    public function testVerifyFalseReference(): void
    {
        $reference = 'MOT2-02-20141105-102030-0';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20131105-102030-99468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20141605-102030-G9468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT-02-20141105-102030-5946831>';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20131105-102030-594*8313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20140231-102030-59468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'OLCS-AA-20141105-102030--9468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'NGT3-02-20141145-102030-90878563';
        $this->assertFalse($this->referenceGenerator->verify($reference));
    }

    public function testInvalidTimeSegment(): void
    {
        $reference = 'MOT2-02-20141105-252030-90876475';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20141105-106930-99468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));

        $reference = 'MOT2-02-20141005-102099-G9468313';
        $this->assertFalse($this->referenceGenerator->verify($reference));
    }

    public function testReferenceUniqueness(): void
    {
        $list = array();
        for ($i = 0; $i < 1000; $i++) {
            $reference = ReferenceGenerator::generate('mot', 1);
            $this->assertFalse(in_array($reference, $list));
            $list[] = $reference;
        }
    }

    public function testInvalidPaymentType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ReferenceGenerator::generate('mot', PaymentScopeCodes::CARD);
    }
}
