<?php

namespace tests;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\Interfaces\MockHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use BlastCloud\Chassis\Chassis;

class ChassisTest extends TestCase
{
    /** @var Chassis */
    public $chassis;

    /** @var MockHandler|MockObject */
    public $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->getMockBuilder(MockHandler::class)
            ->setMethods(['append', 'count'])
            ->getMock();

        $this->chassis = (new class($this) extends Chassis
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
        })->setHandler($this->mockHandler);
    }

    public function testQueueResponseWithResponse()
    {
        $response = (object) ['headers' => ['something'], 'body' => 'some body text'];

        $this->mockHandler->expects($this->once())
            ->method('append')
            ->with($this->equalTo($response));

        $this->chassis->queueResponse($response);
    }

    public function testQueueResponseWithMultiple()
    {
        $this->mockHandler->expects($this->exactly(3))
            ->method('append');

        $this->chassis->queueResponse(['something'], 'second', ['Third']);
    }

    public function testQueueManyWithResponse()
    {
        $response = (object)['header' => ['something'], 'status' => 200, 'body' => 'hello world!'];

        $this->mockHandler->expects($this->exactly(12))
            ->method('append')
            ->with($response);

        $this->chassis->queueMany($response, 12);
    }

    public function testQueueCount()
    {
        $this->mockHandler->expects($this->once())
            ->method('count')
            ->willReturn(3);

        $this->assertEquals(3, $this->chassis->queueCount());
    }

    public function testGetHistory()
    {
        $this->assertEmpty($this->chassis->getHistory());

        $this->chassis->setHistory($history = [
            [
                'item' => 'something',
                'fetch' => 'catch'
            ],
            [
                'farce' => 'Monty Python',
                'fantasy' => 'Stormlight Archives'
            ]
        ]);

        $this->assertEquals($history[1], $this->chassis->getHistory(1));
        $this->assertEquals($history[1]['fantasy'], $this->chassis->getHistory(1, 'fantasy'));
        $this->assertEquals($history, $this->chassis->getHistory());
    }

    public function testHistoryCount()
    {
        $this->assertEquals(0, $this->chassis->historyCount());

        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->assertEquals(2, $this->chassis->historyCount());
    }

    public function testExpectation()
    {
        $this->assertEmpty($this->chassis->getExpectations());

        $expectation = $this->chassis->expects($this->once());

        $this->assertEquals(1, $this->getNumAssertions());
        $this->assertInstanceOf(Expectation::class, $expectation);
        $this->assertCount(1, $this->chassis->getExpectations());
    }
}