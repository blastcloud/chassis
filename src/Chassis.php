<?php

namespace BlastCloud\Chassis;

use BlastCloud\Chassis\Interfaces\MockHandler;
use PHPUnit\Framework\TestCase;

abstract class Chassis
{
    use Assertions;

    protected $handlerStack;

    /** @var MockHandler  */
    protected $mockHandler;

    /** @var Expectation[] */
    protected array $expectations = [];

    /**
     * Run the cascade of expectations made. This
     * method should be called with an "after"
     * annotation in the UsesGuzzler trait.
     */
    protected function runExpectations()
    {
        foreach ($this->expectations as $expectation) {
            $expectation($this->testInstance, $this->history);
        }
    }

    /**
     * Create a client instance with the required handler stacks.
     */
    abstract public function getClient(array $options = []): mixed;

    /**
     * Add a response to the mock queue. All responses
     * will return in the order they are given.
     */
    public function queueResponse(mixed ...$arguments): void
    {
        foreach ($arguments as $response) {
            $this->mockHandler->append($response);
        }
    }

    /**
     * Add a response to the mock queue multiple times.
     */
    public function queueMany(mixed $argument, int $times = 1): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->mockHandler->append($argument);
        }
    }

    /**
     * Get the current count of responses in the mock queue.
     */
    public function queueCount(): int
    {
        return $this->mockHandler->count();
    }

    /**
     * Return the history stack Guzzle builds with each request/response.
     */
    public function getHistory(?int $index = null, ?string $subIndex = null): mixed
    {
        if ($index === null) {
            return $this->history;
        }

        return ($subIndex == null)
            ? $this->history[$index]
            : $this->history[$index][$subIndex];
    }

    /**
     * Return the count of history items.
     */
    public function historyCount(): int
    {
        return count($this->history);
    }

    /**
     * Create a new Expectation instance on which various pieces of the
     * request can be asserted against.
     */
    public function expects(mixed $argument): Expectation
    {
        $this->expectations[] = $expectation = $this->createExpectation($argument);

        // Each expectation is an assertion, but because the assertion
        // won't be tested until the @after method, we should add a
        // count for each new expectation that will be asserted.
        $this->increment();

        return $expectation;
    }

    abstract protected function createExpectation(mixed $argument = null): Expectation;
}