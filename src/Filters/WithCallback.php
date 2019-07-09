<?php

namespace BlastCloud\Chassis\Filters;

use BlastCloud\Chassis\Interfaces\With;

class WithCallback extends Base implements With
{
    /** @var \Closure */
    protected $closure;

    /** @var string */
    protected $message;

    public function withCallback(\Closure $closure, $message = null)
    {
        $this->closure = $closure;
        $this->message = $message;
    }

    public function __invoke(array $history): array
    {
        return array_filter($history, $this->closure);
    }

    public function __toString(): string
    {
        return $this->message ?? "Custom callback: \Closure";
    }
}