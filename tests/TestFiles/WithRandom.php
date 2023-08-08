<?php

namespace Tests\TestFiles;

use BlastCloud\Chassis\Filters\Base;
use BlastCloud\Chassis\Interfaces\With;

class WithRandom extends Base implements With
{
    public static string $first;
    public static string $second;
    public string $endpoint = '/some-endpoint/here';
    public static string $toString = 'something';

    public static function getEndpointString()
    {
        return (new self)->endpoint;
    }

    public function withRandom($first, $second)
    {
        self::$first = $first;
        self::$second = $second;
    }

    public function __invoke(array $history): array
    {
        return $history;
    }

    public function __toString(): string
    {
        return self::$toString;
    }

}