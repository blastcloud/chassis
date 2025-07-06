<?php

namespace Tests\Helpers;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Interfaces\MockHandler;
use BlastCloud\Chassis\Traits\Macros;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\TestFiles\ChassisChild;

/**
 * @covers \BlastCloud\Chassis\Traits\Macros
 */
#[CoversTrait(Macros::class)]
class MacrosTest extends TestCase
{
    public ChassisChild $chassis;

    /** @var MockHandler|MockObject */
    public $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->getMockBuilder(MockHandler::class)
            ->onlyMethods(['append', 'count'])
            ->getMock();

        $this->chassis = (new ChassisChild($this))->setHandler($this->mockHandler);
    }

    public function testMacro()
    {
        Expectation::macro('inlineTest', function (Expectation $expect, $message) {
            return $expect->will($message);
        });

        $this->mockHandler->expects($this->once())
            ->method('append')
            ->with($this->matches('the message'));

        $this->chassis->expects($this->once())
            ->inlineTest('the message');

        $this->assertTrue(in_array('inlineTest', Expectation::macros()));
    }

    public function testOverrideInline()
    {
        Expectation::macro('original', function ($e, $message) {
            return $e->will($message);
        });

        $this->mockHandler->expects($this->once())
            ->method('append')
            ->with($this->matches('fedcba'));

        // Now we override to make it reversed.
        Expectation::macro('original', function ($e, $message) {
            return $e->will(strrev($message));
        });

        $this->chassis->expects($this->never())
            ->original('abcdef');
    }
}