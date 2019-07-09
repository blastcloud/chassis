<?php

namespace tests\testFiles;

use BlastCloud\Chassis\Filters\WithCallback as Base;
use BlastCloud\Chassis\Interfaces\With;

class WithCallback extends Base implements With
{
    public static $mess;

    public function withCallback(\Closure $closure, $message = null)
    {
        self::$mess = $message;
        parent::withCallback($closure, $message);
    }
}
