<?php

/**
 * Interface for a web service that can be notified about errors.
 */

namespace App\WebService;

/**
 * Interface WebServiceInterface
 *
 * @package App\WebService
 */
interface WebServiceInterface
{
    /**
     * Notifies web service about any errors.
     *
     * @param string $message information to notify
     */
    public function notify(string $message): void;
}
