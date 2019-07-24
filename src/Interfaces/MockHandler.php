<?php

namespace BlastCloud\Chassis\Interfaces;

interface MockHandler extends \Countable
{
    /**
    * Add a mock response to the queue of repsonses
    * to return from the client.
    *
    * @param mixed $response
    * @return void
    */
    public function append($response): void;
    
    /**
    * Return the total number of responses currently
    * in the mock queue.
    *
    * @return int
    */
    public function count(): int;
}