<?php

/**
 * Basic test suite for all calculator functionalities.
 */

namespace Test;

use App\Calculator;
use Generator;
use InvalidArgumentException;
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
     * Tests invalid input.
     *
     * @dataProvider invalidAddProvider
     *
     * @param string $string invalid numbers (if any)
     * @param string $expected exception class
     */
    public function testInvalidAdd(string $string, string $expected)
    {
        $this->expectException($expected);
        $this->calculator->add($string);
    }

    /**
     * Tests input with negative numbers.
     *
     * @dataProvider negativeDataProvider
     *
     * @param string $string input with negative numbers
     * @param string $expected exception message
     */
    public function testNegativeAdd(string $string, string $expected)
    {
        $this->expectExceptionMessage($expected);
        $this->calculator->add($string);
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
        yield 'two numbers' => [
            'string' => '1,2',
            'expected' => 3
        ];
        yield 'more numbers' => [
            'string' => '1,2,4,7,8,11',
            'expected' => 33
        ];
        yield 'non-integer' => [
            'string' => '1.9',
            'expected' => 1
        ];
        yield 'high numbers' => [
            'string' => '456, 889',
            'expected' => 1345
        ];
        yield 'ignore numbers bigger then 1000' => [
            'string' => '2,1001',
            'expected' => 2
        ];
        yield 'new line delimeter' => [
            'string' => "1\n2,3",
            'expected' => 6
        ];
        yield 'custom delimeter' => [
            'string' => "//;\n1;2",
            'expected' => 3
        ];
        yield 'custom long delimeter' => [
            'string' => "//[***]\n1***2***3",
            'expected' => 6
        ];
        yield 'multiple delimeters' => [
            'string' => "//[*][%]\n1*2%3",
            'expected' => 6
        ];
    }

    /**
     * Provides test data with invalid input strings.
     * Input string and expected exception class.
     *
     * @return Generator
     */
    public function invalidAddProvider()
    {
        yield 'empty arguments' => [
            'string' => "1,\n",
            'expected' => InvalidArgumentException::class
        ];
        yield 'all commas' => [
            'string' => ',,,,,',
            'expected' => InvalidArgumentException::class
        ];
        yield 'custom long delimeter without format' => [
            'string' => "//;abc[\n6;abc[5",
            'expected' => InvalidArgumentException::class
        ];
    }

    /**
     * Provides test data with negative numbers in input string.
     * Input string and expected exception message.
     *
     * @return Generator
     */
    public function negativeDataProvider()
    {
        yield 'single negative' => [
            'string' => "-1",
            'expected' => 'Negatives not allowed: -1'
        ];
        yield 'multiple negatives' => [
            'string' => '-5,-7,-3',
            'expected' => 'Negatives not allowed: -5, -7, -3'
        ];
    }
}
