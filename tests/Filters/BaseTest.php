<?php

namespace tests\Filters;

use BlastCloud\Chassis\Expectation;
use PHPUnit\Framework\TestCase;
use tests\testFiles\ChassisChild;

class BaseTest extends TestCase
{
    /** @var ChassisChild */
    public $chassis;

    public function setUp(): void
    {
        parent::setUp();

        $this->chassis = new ChassisChild($this);
    }

    public function testAddThrowsErrorWhenMethodNotFound()
    {
        $this->expectException(\Error::class);
        $class = Expectation::class;
        $this->expectExceptionMessage("Call to undefined method {$class}::withBodyDouble()");

        $this->chassis->expects($this->never())
            ->withBodyDouble('anything');
    }
}