<?php

/**
 * Tests for Logger functionalities.
 */

namespace Test\Logger;

use App\Logger\Logger;
use Generator;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * Class LoggerTest
 *
 * @package Test\Logger
 */
class LoggerTest extends TestCase
{
    /**
     * @var Logger $logger object that we operate on
     */
    private $logger;

    /**
     * @var vfsStreamDirectory $root main dir for log files
     */
    private $root;

    /**
     * @var vfsStreamFile $file log file
     */
    private $file;

    /**
     * Sets up fresh logger for each test.
     */
    protected function setUp(): void
    {
        $this->logger = new Logger();
        $this->root = vfsStream::setup('logs');
        $this->file = vfsStream::newFile('test.log')->at($this->root);
    }

    /**
     * Tests whether logger creates desired file.
     */
    public function testLoggerFileExists()
    {
        $this->logger->log('some message', $this->file->url());
        $this->assertFileExists($this->file->url());
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
            $this->logger->log($message, $this->file->url());
        }
        $this->assertEquals($expected, file_get_contents($this->file->url()));
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
        $this->root->removeChild('test.log');
    }
}
