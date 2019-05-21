<?php

/**
 * Custom exception for negative arguments passed in input string.
 */

namespace App;

use InvalidArgumentException;

/**
 * Class NegativeArgumentException
 *
 * @package App
 */
class NegativeArgumentException extends InvalidArgumentException
{
    /**
     * NegativeArgumentException constructor.
     *
     * @param array $negatives
     */
    public function __construct(array $negatives)
    {
        parent::__construct('Negatives not allowed: ' . implode(', ', $negatives));
    }
}
