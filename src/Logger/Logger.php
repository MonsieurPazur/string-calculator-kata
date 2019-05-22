<?php

/**
 * Class used to log calculations results.
 */

namespace App\Logger;

/**
 * Class Logger
 *
 * @package App\Logger
 */
class Logger
{
    /**
     * @var string extension used for log files
     */
    const FILE_EXTENSION = '.log';

    /**
     * @var string location where we store our logs
     */
    const LOG_DIR = __DIR__ . '/../../logs/';

    /**
     * Logs given message.
     *
     * @param string $message text to be logged
     * @param string $filename optional file to log to
     */
    public function log(string $message, string $filename = ''): void
    {
        // Logger will log to default file if we won't force it.
        $filename = $filename ?: $this->getFilename();

        $handle = fopen($filename, 'a');
        fwrite($handle, $message . PHP_EOL);
        fclose($handle);
    }

    /**
     * Gets complete path (with actual filename) to current log file, based on date.
     *
     * @return string complete path to log file
     */
    private function getFilename(): string
    {
        return self::LOG_DIR . date('Y_m_d') . self::FILE_EXTENSION;
    }
}
