<?php

namespace BlastCloud\Chassis\Traits;

use BlastCloud\Chassis\Expectation;
use Closure;

trait Macros
{
    protected static array $macros = [];

    public static function macro(string $method, Closure $callable): void
    {
        static::$macros[$method] = $callable;
    }

    /**
     * Search for a macro by a given name, and if one exists
     * invoke it with any provided arguments.
     */
    public function runMacro(string $method, Expectation $expect, mixed $arguments): bool
    {
        if (!isset(static::$macros[$method])) {
            return false;
        }

        $arguments = [$expect, ...$arguments];
        static::$macros[$method](...$arguments);

        return true;
    }
}