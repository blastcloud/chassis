<?php

namespace BlastCloud\Chassis\Traits;

use BlastCloud\Chassis\Expectation;
use Closure;

trait Macros
{
    protected static $macros = [];

    /**
     * Add a method to the stack.
     *
     * @param string $method
     * @param Closure $callable
     */
    public static function macro($method, Closure $callable)
    {
        self::$macros[$method] = $callable;
    }

    /**
     * Search for a macro by a given name, and if one exists
     * invoke it with any provided arguments.
     *
     * @param string $method
     * @param Expectation $expect
     * @param mixed $arguments
     * @return bool
     */
    public function runMacro($method, Expectation $expect, $arguments)
    {
        if (!isset(self::$macros[$method])) {
            return false;
        }

        array_unshift($arguments, $expect);

        call_user_func_array(self::$macros[$method], $arguments);

        return true;
    }

    /**
     * Return an array of the names of all macros loaded.
     *
     * @return string[]
     */
    public static function macros(): array
    {
        return array_keys(self::$macros);
    }
}