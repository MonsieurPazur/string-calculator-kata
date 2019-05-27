<?php

/**
 * Main calculator functionalities. Basic operations.
 */

namespace App\Calculator;

use App\Exception\NegativeArgumentException;
use App\Logger\LoggerInterface;
use App\WebService\WebServiceInterface;
use Exception;
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
    public const MAIN_STRING_DELIMETER = ',';

    /**
     * @var string delimeter used as default additional delimeter
     */
    public const DEFAULT_DELIMETER = "\n";

    /**
     * @var int every number higher than that should be ignored in calculation
     */
    public const MAXIMUM_NUMBER = 1000;

    /**
     * @var string when at the beginning of input, informs us about delimeter change
     */
    public const DELIMETER_COMMAND = '//';

    /**
     * @var string delimeter can be of any length, but has to follow format: [delimeter]
     */
    public const LONG_DELIMETER_FORMAT = '/^(\[.*\])$/';

    /**
     * @var string[] $delimeters additional, custom delimeters, that may get changed
     */
    private $delimeters;

    /**
     * @var LoggerInterface $logger used to log calculation results
     */
    private $logger;

    /**
     * @var WebServiceInterface $webService used to notify about any errors
     */
    private $webService;

    /**
     * Calculator constructor.
     *
     * @param LoggerInterface|null $logger
     * @param WebServiceInterface|null $webService
     */
    public function __construct(LoggerInterface $logger = null, WebServiceInterface $webService = null)
    {
        $this->delimeters[] = self::DEFAULT_DELIMETER;
        $this->logger = $logger;
        $this->webService = $webService;
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
        $numbersArr = $this->prepareNumbers($numbers);
        $sum = 0;
        foreach ($numbersArr as $number) {
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
        $numbersArr = explode(self::MAIN_STRING_DELIMETER, $numbers);
        $this->validateNumbers($numbers);
        foreach ($numbersArr as &$number) {
            $number = (int)$number;
        }
        return $numbersArr;
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
                throw new InvalidArgumentException('Empty argument is not allowed.');
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
        if (0 !== strpos($numbers, self::DELIMETER_COMMAND)) {
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
                throw new InvalidArgumentException('Incorrect long delimiter format.');
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
        if (null !== $this->logger) {
            try {
                $this->logger->log((string)$result);
            } catch (Exception $e) {
                if (null !== $this->webService) {
                    $this->webService->notify('Logging has failed: ' . $e->getMessage());
                }
            }
        }
    }
}
