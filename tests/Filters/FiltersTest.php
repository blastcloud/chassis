<?php

namespace tests\Filters;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Interfaces\MockHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use tests\testFiles\{ChassisChild, WithCallback, WithTest};

class FiltersTest extends TestCase
{
    /** @var ChassisChild */
    public $chassis;

    /** @var MockHandler|MockObject */
    public $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->getMockBuilder(MockHandler::class)
            ->setMethods(['append', 'count'])
            ->getMock();

        $this->chassis = (new ChassisChild($this))->setHandler($this->mockHandler);
    }

    public function testAddNamespace()
    {
        $this->chassis->expects($this->once())
            ->withTest('something', 'another')
            ->will(['something']);

        $this->chassis->setHistory([
            ['first']
        ]);

        $this->assertEquals('something', WithTest::$first);
        $this->assertEquals('another', WithTest::$second);
    }

    public function testCustomOverrides()
    {
        Expectation::addNamespace('tests\\testFiles');

        $message = 'my special body';

        $this->chassis->expects($this->once())
            ->withCallback(function () {}, $message)
            ->will(['something']);

        $this->assertEquals($message, WithCallback::$mess);
    }

    public function testAddNamespaceAndNamespaces()
    {
        $this->assertCount(2, Expectation::namespaces());

        Expectation::addNamespace('GuzzleHttp');

        $this->assertContains('GuzzleHttp', Expectation::namespaces());
    }
}