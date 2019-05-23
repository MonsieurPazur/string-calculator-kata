<?php

/**
 * Test suite for logging calculations.
 */

namespace Test\Calculator;

use App\Calculator\Calculator;
use App\Logger\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
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
     * @var MockObject|LoggerInterface $logger mock
     */
    private $logger;

    /**
     * @var Calculator $calculator object that we operate on
     */
    private $calculator;

    /**
     * Sets up logger mock.
     *
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)
            ->setMethods(['log'])
            ->getMock();
        $this->calculator = new Calculator($this->logger);
    }

    /**
     * Tests whether we called log method or not.
     */
    public function testLoggingAdd()
    {
        $this->logger->expects($this->once())
            ->method('log')
            ->with($this->equalTo('5'));
        $this->calculator->add('2,3');
    }
}
