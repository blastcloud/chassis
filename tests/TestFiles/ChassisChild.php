<?php

namespace Tests\TestFiles;

use BlastCloud\Chassis\Chassis;
use BlastCloud\Chassis\Expectation;

/**
 * This class only exists to be a concrete class for the Chassis class.
 * All methods in this class either just complete the abstracts or
 * are helpers for making testing easier.
 *
 * Class ChassisChild
 * @package Tests\TestFiles
 */
class ChassisChild extends Chassis
{
    public function getClient(array $options = []): mixed {}

    public function setHandler($handler): static
    {
        $this->mockHandler = $handler;
        return $this;
    }

    public function setHistory($history): void
    {
        $this->history = $history;
    }

    public function getExpectations(): array
    {
        return $this->expectations;
    }

    public function runParentExpectations(): void
    {
        $this->runExpectations();
    }

    protected function createExpectation($argument = null): Expectation
    {
        return new Expectation($argument, $this);
    }
}