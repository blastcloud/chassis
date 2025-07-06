<?php

namespace Tests\Filters;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Filters\Base;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\TestFiles\ChassisChild;

/**
 * @covers \BlastCloud\Chassis\Filters\Base
 */
#[CoversClass(Base::class)]
class BaseTest extends TestCase
{
    public ChassisChild $chassis;

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