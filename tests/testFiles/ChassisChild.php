<?php

namespace tests\testFiles;

use BlastCloud\Chassis\Chassis;
use BlastCloud\Chassis\Expectation;
use PHPUnit\Framework\MockObject\Matcher\InvokedRecorder;

/**
 * This class only exists to be a concrete class for the Chassis class.
 * All methods in this class either just complete the abstracts or
 * are helpers for making testing easier.
 *
 * Class ChassisChild
 * @package tests\testFiles
 */
class ChassisChild extends Chassis
{
    public function getClient(array $options = []) {}

    public function setHandler($handler) {
        $this->mockHandler = $handler;
        return $this;
    }

    public function setHistory($history) {
        $this->history = $history;
    }

    public function getExpectations() {
        return $this->expectations;
    }

    public function runParentExpectations()
    {
        $this->runExpectations();
    }

    protected function createExpectation(?InvokedRecorder $argument = null)
    {
        return new Expectation($argument, $this);
    }
}