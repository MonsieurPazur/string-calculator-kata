<?php

/**
 * Main calculator functionalities. Basic operations.
 */

namespace App;

use InvalidArgumentException;

/**
 * Class Calculator
 *
 * @package App
 */
class Calculator
{
    /**
     * @var string delimeter for splitting numbers in strings
     */
    const STRING_DELIMETER = ',';

    /**
     * @var int maximum amount of delimeters in given string
     */
    const MAX_DELIMETERS = 1;

    /**
     * Adds two comma separated numbers.
     *
     * @param string $numbers two comma separated numbers
     *
     * @return int sum of given numbers
     */
    public function add(string $numbers): int
    {
        $this->validateAdd($numbers);
        $numbers = explode(self::STRING_DELIMETER, $numbers);
        if (isset($numbers[1])) {
            return (int)$numbers[0] + (int)$numbers[1];
        } else {
            return (int)$numbers[0];
        }
    }

    /**
     * Checks whether given string of numbers is valid in terms of calculator.
     *
     * @param string $numbers given numbers
     */
    private function validateAdd(string $numbers): void
    {
        if (substr_count($numbers, self::STRING_DELIMETER) > self::MAX_DELIMETERS) {
            throw new InvalidArgumentException();
        }
    }
}
