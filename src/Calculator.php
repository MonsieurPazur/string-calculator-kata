<?php

/**
 * Main calculator functionalities. Basic operations.
 */

namespace App;

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
     * Adds two comma separated numbers.
     *
     * @param string $numbers two comma separated numbers
     *
     * @return int sum of given numbers
     */
    public function add(string $numbers): int
    {
        $numbers = explode(self::STRING_DELIMETER, $numbers);
        if (isset($numbers[1])) {
            return (int)$numbers[0] + (int)$numbers[1];
        } else {
            return (int)$numbers[0];
        }
    }
}
