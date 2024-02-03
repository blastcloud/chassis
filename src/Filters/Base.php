<?php

namespace BlastCloud\Chassis\Filters;

use BlastCloud\Chassis\Expectation;

abstract class Base
{
    const STR_PAD = 10;

    public function add(string $name, array $args): void
    {
        if (!method_exists($this, $name)) {
            throw new \Error(sprintf("Call to undefined method %s::%s()", Expectation::class, $name));
        }

        $this->$name(...$args);
    }
}