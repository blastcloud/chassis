<?php

namespace BlastCloud\Chassis;

use PHPUnit\Framework\{ TestCase, Assert };

trait Assertions
{
    protected array $history = [];

    public function __construct(protected  TestCase $testInstance)
    { }

    protected function increment(): void
    {
        $this->testInstance->addToAssertionCount(1);
    }

    /**
     * Assert that no requests have been called on the client.
     */
    public function assertNoHistory(?string $message = null): void
    {
        $this->assertHistoryCount(
            0,
            $message ?? 'Failed asserting that the client has no history.'
        );
    }

    /**
     * Assert that the specified number of requests have been made.
     */
    public function assertHistoryCount(int $count, ?string $message = null): void
    {
        $r = $count == 1 ? 'request' : 'requests';

        $this->assert(
            count($this->history) == $count,
            $message ?? "Failed asserting that the client received {$count} {$r}."
        );
    }

    /**
     * @throws UndefinedIndexException
     */
    protected function findOrFailIndexes(array $indexes): array
    {
        return array_map(function ($i) {
            if (!isset($this->history[$i])) {
                throw new UndefinedIndexException("The client history does not have a [{$i}] index.");
            }

            return $this->history[$i];
        }, $indexes);
    }

    /**
     * Run Filters from the closure Expectation with a specific subset of history.
     */
    protected function runClosure(array $history, \Closure $closure, mixed $e): mixed
    {
        $closure($e);

        return (function ($h) {
            return $this->runFilters($h);
        })->call($e, $history);
    }

    /**
     * This is really just a convenience method to save a few repeated lines
     * for each assert method.
     */
    protected function assert(bool $test, string $message): void
    {
        if (!$test) {
            Assert::fail($message);
        }

        $this->increment();
    }

    /**
     * Assert that the first request meets expectations.
     * @throws UndefinedIndexException
     */
    public function assertFirst(\Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            $this->findOrFailIndexes([0]),
            $closure,
            $e = $this->createExpectation()
        );

        $this->assert(
            count($h) == 1,
            $message ?? 'Failed asserting that the first request met expectations.' . $e
        );
    }

    /**
     * Assert that the first request does not met expectations.
     *
     * @throws UndefinedIndexException
     */
    public function assertNotFirst(\Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            $this->findOrFailIndexes([0]),
            $closure,
            $e = $this->createExpectation()
        );

        $this->assert(
            count($h) < 1,
            $message ?? 'Failed asserting that the first request did not meet expectations. ' . $e
        );
    }

    protected function getLast()
    {
        return $this->history[max(array_keys($this->history))];
    }

    /**
     * Assert that the last request meets expectations.
     */
    public function assertLast(\Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            [$this->getLast()],
            $closure,
            $e = $this->createExpectation()
        );

        $this->assert(
            count($h) == 1,
            $message ?? 'Failed asserting that the last request met expectations.' . $e
        );
    }

    /**
     * Assert that the last request does not meet expectations.
     */
    public function assertNotLast(\Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            [$this->getLast()],
            $closure,
            $e = $this->createExpectation()
        );

        $this->assert(
            count($h) == 0,
            $message ?? 'Failed asserting the the last request did not meet expectations. ' . $e
        );
    }

    /**
     * Assert that every request, regardless of count, meet expectations.
     * @throws UndefinedIndexException
     */
    public function assertAll(\Closure $closure, $message = null): void
    {
        if (empty($this->history)) {
            throw new UndefinedIndexException("Client history is currently empty.");
        }

        $this->assertIndexes(array_keys($this->history), $closure, $message);
    }

    /**
     * Assert that a subset of history meets expectations.
     *
     * @throws UndefinedIndexException
     */
    public function assertIndexes(array $indexes, \Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            $this->findOrFailIndexes($indexes),
            $closure,
            $e = $this->createExpectation()
        );

        $diff = array_diff($indexes, array_keys($h));

        $this->assert(
            empty($diff),
            $message ?? "Failed asserting that indexes [" . implode(',', $diff) . "] met expectations." . $e
        );
    }

    /**
     * Assert that a subset of history does not meet expectations.
     *
     * @throws UndefinedIndexException
     */
    public function assertNotIndexes(array $indexes, \Closure $closure, ?string $message = null): void
    {
        $h = $this->runClosure(
            $this->findOrFailIndexes($indexes),
            $closure,
            $e = $this->createExpectation()
        );

        $intersect = array_intersect_key(array_keys($h), $indexes);

        $this->assert(
            empty($intersect),
            $message ?? 'Failed asserting that indexes [' . implode(',',
                $intersect) . '] did not meet expectations.' . $e
        );
    }

    /**
     * Assert that no requests match the expectation.
     *
     * @throws UndefinedIndexException
     */
    public function assertNone(\Closure $closure, $message = null): void
    {
        $this->assertNotIndexes(array_keys($this->history), $closure, $message);
    }
}