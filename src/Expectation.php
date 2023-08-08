<?php

namespace BlastCloud\Chassis;

use BlastCloud\Chassis\Filters\Filters;
use BlastCloud\Chassis\Traits\Macros;
use PHPUnit\Framework\{Assert, ExpectationFailedException, SelfDescribing, TestCase};
use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;

/**
 * Class Expectation
 * @package Chassis
 * @method $this withCallback(\Closure $callback, string $message = null)
 */
class Expectation
{
    use Filters, Macros;

    protected ?Chassis $chassis;

    protected ?SelfDescribing $times;

    /**
     * Each value in this array becomes a convenience method over endpoint().
     */
    public const VERBS = [
        'get',
        'post',
        'put',
        'delete',
        'patch',
        'options'
    ];

    /**
     * Expectation constructor.
     * @param null|InvokedCount $times
     * @param null|Chassis $chassis
     */
    public function __construct($times = null, $chassis = null)
    {
        $this->times = $times;
        $this->chassis = $chassis;
    }

    /**
     * This is used exclusively for the convenience verb methods.
     *
     * @param string $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if ($this->runMacro($name, $this, $arguments)) {
            return $this;
        }

        // Next try to see if it's a with* method we can use.
        if ($filter = $this->isFilter($name)) {
            $filter->add($name, $arguments);
            return $this;
        }

        throw new \Error(sprintf("Call to undefined method %s::%s()", __CLASS__, $name));
    }

    /**
     * Set a follow through; either response, callable, or Exception.
     *
     * @param $response
     * @param int $times
     * @return $this
     */
    public function will($response, int $times = 1)
    {
        for ($i = 0; $i < $times; $i++) {
            $this->chassis->queueResponse($response);
        }

        return $this;
    }

    /**
     * An alias of 'will'.
     *
     * @param $response
     * @param int $times
     * @return $this
     */
    public function willRespond($response, int $times = 1)
    {
        $this->will($response, $times);

        return $this;
    }

    protected function runFilters(array $history)
    {
        foreach ($this->filters as $filter) {
            $history = $filter($history);
        }

        return $history;
    }

    /**
     * Iterate over the history and verify the invocations against it.
     *
     * @param TestCase $instance
     * @param array $history
     */
    public function __invoke(TestCase $instance, array $history): void
    {
        $mock = $instance->getMockBuilder(\stdClass::class)->getMock();

        foreach ($this->runFilters($history) as $i) {
            $this->times->invoked(new Invocation('', '', [], '', $mock));
        }

        try {
            // Invocation Counts
            $this->times->verify();
        } catch (ExpectationFailedException $e) {
            Assert::fail($e->getMessage() . ' ' . $this->__toString());
        }
    }

    public function __toString()
    {
        $endpoint = $messages = '';

        foreach ($this->filters as $filter) {
            $messages .= $filter->__toString() . "\n";
            if (property_exists($filter, 'endpoint')) {
                $endpoint = $filter->endpoint;
            }
        }

        return <<<MESSAGE


Expectation: {$endpoint}
-----------------------------
{$messages}
MESSAGE;
    }
}