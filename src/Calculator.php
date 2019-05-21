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
     * Adds comma separated numbers.
     *
     * @param string $numbers unknown amount of comma separated numbers
     *
     * @return int sum of given numbers
     */
    public function add(string $numbers): int
    {
        $numbers = explode(self::STRING_DELIMETER, $numbers);
        $sum = 0;
        foreach ($numbers as $number) {
            $sum += (int)$number;
        }
        return $sum;
    }
}
