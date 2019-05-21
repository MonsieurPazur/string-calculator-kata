<?php

/**
 * Test suite for logging calculations.
 */

namespace Test;

use App\Calculator;
use App\Logger\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class StringCalculatorLoggingTest
 *
 * @package Test
 */
class StringCalculatorLoggingTest extends TestCase
{
    /**
     * Tests whether we called log method or not.
     *
     * @throws ReflectionException
     */
    public function testLoggingAdd()
    {
        $logger = $this->getMockBuilder(Logger::class)->setMethods(['log'])->getMock();
        $logger->expects($this->once())->method('log')->with($this->equalTo('5'));

        /** @var Logger $logger */
        $calculator = new Calculator($logger);
        $calculator->add('2,3');
    }
}
