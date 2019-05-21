<?php

/**
 * Basic test suite for all calculator functionalities.
 */

namespace Test;

use App\Calculator;
use PHPUnit\Framework\TestCase;

/**
 * Class StringCalculatorTest
 * @package Test
 */
class StringCalculatorTest extends TestCase
{
    public function testAdd()
    {
        $calculator = new Calculator();
        $this->assertEquals(0, $calculator->add(""));
    }
}
