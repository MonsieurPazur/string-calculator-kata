<?php

/**
 * Simple interface for logging funcionality.
 */

namespace App\Logger;

/**
 * Interface LoggerInterface
 *
 * @package App\Logger
 */
interface LoggerInterface
{
    /**
     * Logs given message.
     *
     * @param string $message text to be logged
     * @param string $filename optional file to log to
     */
    public function log(string $message, string $filename = ''): void;
}
