<?php

namespace BlastCloud\Chassis\Interfaces;

/**
 * Interface With
 * @package BlastCloud\Chassis\Interfaces
 * @codeCoverageIgnore
 */
interface With
{
    /**
     * Add values to the filter.
     */
    public function add(string $name, array $arguments): void;

    /**
     * Filter through the history items and return only
     * the items that match.
     */
    public function __invoke(array $history): array;

    /**
     * Return a human-readable representation of what this
     * with* statement added.
     */
    public function __toString(): string;
}