<?php

namespace tests\testFiles;

use BlastCloud\Chassis\Filters\Base;
use BlastCloud\Chassis\Interfaces\With;

/**
 * This class is made so that specific indexes from the history will be
 * returned no matter what. Used for assertions testing, or anywhere
 * that is needed.
 *
 * Class WithIndexes
 * @package tests\testFiles
 */
class WithIndexes extends Base implements With
{
    public $indexes = [];

    public function withIndexes(array $indexes)
    {
        $this->indexes = $indexes;
    }

    public function __invoke(array $history): array
    {
        return array_filter($history, function (...$args) {
            return in_array(array_keys($args)[0], $this->indexes);
        });
    }

    public function __toString(): string
    {
        return str_pad('WithIndexes: ', self::STR_PAD)
            . json_encode($this->indexes, JSON_PRETTY_PRINT);
    }

}