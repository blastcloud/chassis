<?php

namespace BlastCloud\Chassis\Interfaces;

interface MockHandler extends \Countable
{
    public function append($response);
}