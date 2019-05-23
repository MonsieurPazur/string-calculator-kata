<?php

/**
 * Test suite for logging calculations.
 */

namespace Test\Calculator;

use App\Calculator\Calculator;
use App\Logger\LoggerInterface;
use App\WebService\WebServiceInterface;
use Exception;
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
     * @var MockObject|WebServiceInterface $webService mock
     */
    private $webService;

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
        $this->webService = $this->getMockBuilder(WebServiceInterface::class)
            ->setMethods(['notify'])
            ->getMock();
        $this->calculator = new Calculator($this->logger, $this->webService);
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

    /**
     * Tests wheter we notified web service about logger error or not.
     */
    public function testLoggingException()
    {
        $this->logger->expects($this->once())
            ->method('log')
            ->with($this->equalTo('5'))
            ->will($this->throwException(new Exception()));

        $this->webService->expects($this->once())
            ->method('notify')
            ->with($this->equalTo('Logging has failed: '));

        $this->calculator->add('2,3');
    }
}
