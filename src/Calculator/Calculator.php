<?php

/**
 * Main calculator functionalities. Basic operations.
 */

namespace App\Calculator;

use App\Exception\NegativeArgumentException;
use App\Logger\LoggerInterface;
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
     * @var int every number higher than that should be ignored in calculation
     */
    const MAXIMUM_NUMBER = 1000;

    /**
     * @var string when at the beginning of input, informs us about delimeter change
     */
    const DELIMETER_COMMAND = '//';

    /**
     * @var string delimeter can be of any length, but has to follow format: [delimeter]
     */
    const LONG_DELIMETER_FORMAT = '/^(\[.*\])$/';

    /**
     * @var string[] $delimeters additional, custom delimeters, that may get changed
     */
    private $delimeters;

    /**
     * @var LoggerInterface $logger used to log calculation results
     */
    private $logger;

    /**
     * Calculator constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->delimeters[] = self::DEFAULT_DELIMETER;
        $this->logger = $logger;
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
            if ($number <= self::MAXIMUM_NUMBER) {
                $sum += $number;
            }
        }
        $this->log($sum);
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

        // Next we unify all delimeters into main.
        foreach ($this->delimeters as $delimeter) {
            $numbers = str_replace($delimeter, self::MAIN_STRING_DELIMETER, $numbers);
        }

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

            // Also don't allow negative numbers.
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
        if (self::DELIMETER_COMMAND !== substr($numbers, 0, 2)) {
            return $numbers;
        }

        // Else we extract command (+1 in length is for compensating newline).
        $command = substr($numbers, 0, strpos($numbers, "\n") + 1);

        // We remove command from numbers string.
        $numbers = str_replace($command, '', $numbers);

        // We extract custom delimeter from command.
        $delimeter = substr($command, 2, -1);
        $this->changeDelimeter($delimeter);

        return $numbers;
    }

    /**
     * Handles validating and changing delimeter.
     *
     * @param string $delimeter raw delimeter to be validated and changed
     */
    private function changeDelimeter(string $delimeter): void
    {
        if (strlen($delimeter) > 1) {
            if (preg_match(self::LONG_DELIMETER_FORMAT, $delimeter)) {
                // Splitting delimeter into sub delimeters and storing them.
                $delimeters = $this->splitDelimeter($delimeter);
                foreach ($delimeters as $subDelimeter) {
                    $this->delimeters[] = $subDelimeter;
                }
            } else {
                throw new InvalidArgumentException();
            }
        } else {
            $this->delimeters[] = $delimeter;
        }
    }

    /**
     * Splits delimeter into multiple sub delimeters.
     *
     * @param string $delimeter raw delimeter that may consist of sub delimeters
     *
     * @return array sub delimeters
     */
    private function splitDelimeter(string $delimeter): array
    {
        $delimeters = [];
        $subDelimeter = '';

        // We check each character individually.
        foreach (str_split($delimeter) as $char) {
            // Ignore opening brackets.
            if ('[' === $char) {
                continue;
            }

            // If closing brackets, we store current subDelimeter.
            if (']' === $char) {
                $delimeters[] = $subDelimeter;
                $subDelimeter = '';
                continue;
            }
            $subDelimeter .= $char;
        }
        return $delimeters;
    }

    /**
     * Wrapper for logging method.
     *
     * @param int $result calculation result
     */
    private function log(int $result)
    {
        if (!is_null($this->logger)) {
            $this->logger->log((string)$result);
        }
    }
}
