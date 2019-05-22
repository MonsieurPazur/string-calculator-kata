<?php

/**
 * Tests for Logger functionalities.
 */

namespace Test\Logger;

use App\Logger\Logger;
use Generator;
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
     * Tests whether logger creates desired file.
     */
    public function testLoggerFileExists()
    {
        $this->logger->log('some message', self::TEST_FILE);
        $this->assertFileExists(self::TEST_FILE);
    }

    /**
     * Tests whether logger writes messages to file.
     *
     * @dataProvider messagesProvider
     *
     * @param array $messages list of messages to be written to file
     * @param string $expected content of that file after logging
     */
    public function testLoggerWritesMessages(array $messages, string $expected)
    {
        foreach ($messages as $message) {
            $this->logger->log($message, self::TEST_FILE);
        }
        $this->assertEquals($expected, file_get_contents(self::TEST_FILE));
    }

    /**
     * Provides test data for logging messages to file.
     * Input is an array of messages and then expected is content of that file.
     *
     * @return Generator
     */
    public function messagesProvider()
    {
        yield 'single line' => [
            'messages' => [
                'some message'
            ],
            'expected' => 'some message' . PHP_EOL
        ];
        yield 'multi line' => [
            'messages' => [
                'some message',
                'another message'
            ],
            'expected' => 'some message' . PHP_EOL . 'another message' . PHP_EOL
        ];
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
