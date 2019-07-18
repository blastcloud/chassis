<?php

namespace tests\testFiles;

use BlastCloud\Chassis\Filters\Base;
use BlastCloud\Chassis\Interfaces\With;

class WithTest extends Base implements With
{
    public static $first;
    public static $second;
    public $endpoint = '/some-endpoint/here';
    public static $toString = 'something';

    public static function getEndpointString()
    {
        return (new self)->endpoint;
    }

    public function withTest($first, $second)
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