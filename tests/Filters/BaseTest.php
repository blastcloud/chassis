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

    public function testFindsClassButNotMethod()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("Call to undefined method ".Expectation::class."::withCallbackExtended()");

        // First with real
        $this->chassis->expects($this->once())
            ->withCallback(function() { return false; })
            ->withCallback(function () { return true; });

        // Second with non-real
        $this->chassis->expects($this->once())
            ->withCallbackExtended('something');
    }
}