<?php

namespace BlastCloud\Chassis\Interfaces;

interface MockHandler extends \Countable
{
    /**
    * Add a mock response to the queue of repsonses
    * to return from the client.
    */
    public function append(mixed $response): void;
    
    /**
    * Return the total number of responses currently
    * in the mock queue.
    */
    public function count(): int;
}