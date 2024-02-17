<?php

namespace Tests;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Interfaces\MockHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\TestFiles\ChassisChild;

/**
 * @covers \BlastCloud\Chassis\Expectation
 */
class ExpectationTest extends TestCase
{
    public ChassisChild $chassis;

    public MockHandler|MockObject $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->getMockBuilder(MockHandler::class)
            ->onlyMethods(['append', 'count'])
            ->getMock();

        $this->chassis = (new ChassisChild($this))->setHandler($this->mockHandler);

        Expectation::addNamespace('Tests\\TestFiles');
    }

    public function testExpectsReturnsExpectationInstanceAndIsChainable()
    {
        $result = $this->chassis->expects($this->never())
            ->withIndexes([]);

        $this->assertInstanceOf(Expectation::class, $result);
    }

    public function testInvocationPassing()
    {
        $expectation = $this->chassis->expects($this->once())
            ->withIndexes([0])
            ->willRespond(['first']);

        $this->chassis->setHistory([
            ['request' => (object)['key' => 'value']]
        ]);

        $expectation($this, $this->chassis->getHistory());
    }

    public function testInvocationsFails()
    {
        $expectation = (new Expectation($this->once()))
            ->withIndexes([]);

        try {
            $expectation($this, []);
            $this->fail('Did not throw an invocation fail.');
        } catch (\Exception $e) {
            $this->assertFalse(strstr($e->getMessage(), (string)$expectation) === false);
        }
    }

    public function testWillAndWillRespond()
    {
        $this->mockHandler->expects($this->once())
            ->method('append');
        $this->mockHandler->method('count')
            ->willReturn(1);

        $this->chassis->expects($this->once())
            ->willRespond(['oiuoi']);

        $this->assertEquals(1, $this->chassis->queueCount());
    }

    public function testUnknownMacro()
    {
        $this->expectException(\Error::class);

        $this->chassis->expects($this->never())
            ->something('/a-url');
    }

    public function testFailureWhenWithNotFound()
    {
        $this->expectException(\Error::class);
        $class = Expectation::class;
        $this->expectExceptionMessage("Call to undefined method {$class}::withDoesNotExist");

        $this->chassis->expects($this->never())
            ->withDoesNotExist('something');
    }
}