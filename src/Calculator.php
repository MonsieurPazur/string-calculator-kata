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
     * Adds two comma separated numbers.
     *
     * @param string $numbers two comma separated numbers
     *
     * @return int sum of given numbers
     */
    public function add(string $numbers): int
    {
        return (int)$numbers;
    }
}
