<?php

namespace Tests\Filters;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Interfaces\MockHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\TestFiles\{ChassisChild, WithCallback, WithRandom};

class FiltersTest extends TestCase
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
    }

    public function testAddNamespace()
    {
        $this->chassis->expects($this->once())
            ->withRandom('something', 'another')
            ->will(['something']);

        $this->chassis->setHistory([
            ['first']
        ]);

        $this->assertEquals('something', WithRandom::$first);
        $this->assertEquals('another', WithRandom::$second);
    }

    public function testCustomOverrides()
    {
        Expectation::addNamespace('Tests\\TestFiles');

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

    public function testErrorStrings()
    {
        $this->chassis->setHistory([
            ['something']
        ]);

        try {
            $this->chassis->assertAll(function (Expectation $e) {
                return $e->withRandom('something', 'other')
                    ->withCallback(function ($e) {
                        return false;
                    });
            });
        } catch (\Throwable $exception) {
            // TODO: Once support for PHPUnit 7 is dropped, change these to
            // assertStringContainsString()
            $this->assertNotFalse(strpos($exception->getMessage(), WithRandom::getEndpointString()));
            $this->assertNotFalse(strpos($exception->getMessage(), WithRandom::$toString));
        }
    }
}