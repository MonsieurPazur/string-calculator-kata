<?php

/**
 * Tests for Logger functionalities.
 */

namespace Test\Logger;

use App\Logger\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggerTest
 *
 * @package Test\Logger
 */
class LoggerTest extends TestCase
{
    /**
     * @var string log file for testing
     */
    const TEST_FILE = __DIR__ . '/test.log';

    /**
     * @var Logger $logger object that we operate on
     */
    private $logger;

    /**
     * Sets up fresh logger for each test.
     */
    protected function setUp(): void
    {
        $this->logger = new Logger();
    }

    /**
     * Tests logging messages into files.
     */
    public function testLogger()
    {
        $this->logger->log('some message', self::TEST_FILE);
        $this->assertFileExists(self::TEST_FILE);
    }

    /**
     * Removes test file.
     */
    protected function tearDown(): void
    {
        if (file_exists(self::TEST_FILE)) {
            unlink(self::TEST_FILE);
        }
    }

}
