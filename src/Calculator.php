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
     * @var string delimeter used as default additional delimeter
     */
    const DEFAULT_DELIMETER = "\n";

    /**
     * @var string $delimeter addition, custom delimeter, that may get changed
     */
    private $delimeter;

    /**
     * Calculator constructor.
     */
    public function __construct()
    {
        $this->delimeter = self::DEFAULT_DELIMETER;
    }

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
        // First we remove (optional) custom delimeter command and change delimeter.
        $numbers = $this->handleCustomDelimeter($numbers);

        // Next we unify both delimeters into main.
        $numbers = str_replace($this->delimeter, self::MAIN_STRING_DELIMETER, $numbers);

        // Lastly we explode numbers string into array.
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
        $negatives = [];
        foreach ($numbers as $number) {
            // We don't allow empty arguments.
            if ('' === $number) {
                throw new InvalidArgumentException();
            }
            if ((int)$number < 0) {
                $negatives[] = $number;
            }
        }
        if (!empty($negatives)) {
            throw new NegativeArgumentException($negatives);
        }
    }

    /**
     * Handles change of delimeter and also removes command from numbers string.
     *
     * @param string $numbers input numbers with (optional) command
     *
     * @return string numbers without command
     */
    private function handleCustomDelimeter(string $numbers): string
    {
        // If there's no command, we simply return $numbers unchanged.
        if ('//' !== substr($numbers, 0, 2)) {
            return $numbers;
        }

        // Else we extract command (+1 in length is for compensating newline).
        $command = substr($numbers, 0, strpos($numbers, "\n") + 1);

        // We remove command from numbers string.
        $numbers = str_replace($command, '', $numbers);

        // We extract custom delimeter from command.
        // Start is '//' and length is compensating for "\n".
        $this->delimeter = substr($command, 2, -1);

        return $numbers;
    }
}
