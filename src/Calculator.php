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
     * @var string main delimeter for splitting numbers in strings
     */
    const MAIN_STRING_DELIMETER = ',';

    /**
     * @var string[] all additional delimeters for spliiting numbers
     */
    const STRING_DELIMETERS = ["\n"];

    /**
     * Adds comma separated numbers.
     *
     * @param string $numbers unknown amount of comma separated numbers
     *
     * @return int sum of given numbers
     */
    public function add(string $numbers): int
    {
        // Specific case.
        if ('' === $numbers) {
            return 0;
        }
        $numbers = $this->prepareNumbers($numbers);
        $sum = 0;
        foreach ($numbers as $number) {
            $sum += $number;
        }
        return $sum;
    }

    /**
     * Prepares numbers by splitting input string into array of ints.
     *
     * @param string $numbers raw input string
     *
     * @return array prepared and casted to ints numbers
     */
    private function prepareNumbers(string $numbers): array
    {
        foreach (self::STRING_DELIMETERS as $delimeter) {
            $numbers = str_replace($delimeter, self::MAIN_STRING_DELIMETER, $numbers);
        }
        $numbers = explode(self::MAIN_STRING_DELIMETER, $numbers);
        $this->validateNumbers($numbers);
        foreach ($numbers as &$number) {
            $number = (int)$number;
        }
        return $numbers;
    }

    /**
     * Validates input numbers.
     *
     * @param array $numbers prepared (but not yet casted) numbers
     */
    private function validateNumbers(array $numbers): void
    {
        foreach ($numbers as $number) {
            // We don't allow empty arguments.
            if ('' === $number) {
                throw new InvalidArgumentException();
            }
        }
    }
}
