<?php

namespace Tests\TestFiles;

use BlastCloud\Chassis\Filters\WithCallback as Base;
use BlastCloud\Chassis\Interfaces\With;

class WithCallback extends Base implements With
{
    public static ?string $mess;

    public function withCallback(\Closure $closure, $message = null): void
    {
        self::$mess = $message;
        parent::withCallback($closure, $message);
    }
}
