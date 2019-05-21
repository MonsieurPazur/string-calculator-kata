<?php

/**
 * Basic test suite for all calculator functionalities.
 */

namespace Test;

use App\Calculator;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class StringCalculatorTest
 *
 * @package Test
 */
class StringCalculatorTest extends TestCase
{
    /**
     * @var Calculator $calculator object that we operate on
     */
    private $calculator;

    /**
     * Setting up fresh String Calculator for each test.
     */
    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    /**
     * Tests adding two (or less) numbers.
     *
     * @dataProvider addProvider
     *
     * @param string $string given string with values
     * @param int $expected sum of given numbers
     */
    public function testAdd(string $string, int $expected)
    {
        $this->assertEquals($expected, $this->calculator->add($string));
    }

    /**
     * Provides test data for additions tests.
     * Input string and expected value after calculating.
     *
     * @return Generator
     */
    public function addProvider()
    {
        yield 'empty string' => [
            'string' => '',
            'expected' => 0
        ];
        yield 'one number' => [
            'string' => '1',
            'expected' => 1
        ];
    }
}
