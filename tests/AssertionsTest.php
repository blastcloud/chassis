<?php

namespace Tests;

use BlastCloud\Chassis\Expectation;
use BlastCloud\Chassis\UndefinedIndexException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Tests\TestFiles\ChassisChild;

class AssertionsTest extends TestCase
{
    /** @var ChassisChild */
    public $chassis;

    public $options = [
        'headers' => ['Guzzler' => '**the-values**']
    ];

    /** @var \Closure */
    public $dumbClosure;

    /** @var string */
    public static $regexMethodName = 'expectExceptionMessageMatches';

    public static function setUpBeforeClass(): void
    {
        if (!method_exists(self::class, self::$regexMethodName)) {
            self::$regexMethodName = 'expectExceptionMessageRegExp';
        }
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->chassis = new ChassisChild($this);
        Expectation::addNamespace('Tests\\TestFiles');
    }

    public function testAssertNoHistoryPasses()
    {
        $this->chassis->assertNoHistory();
    }

    public function testAssertNoHistoryFailsDefaultMessage()
    {
        $this->chassis->setHistory([
            ['something']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\bno history\b/");

        $this->chassis->assertNoHistory();
    }

    public function testAssertNoHistoryFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
           ['something']
        ]);

        $message = 'some special message';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $this->chassis->assertNoHistory($message);
    }

    public function testAssertHistoryCountPasses()
    {
        $this->chassis->assertHistoryCount(0);

        $this->chassis->setHistory([
            ['first'], ['second']
        ]);
        $this->chassis->assertHistoryCount(2);
    }

    public function testAssertHistoryCountFailsDefaultMessageOneRequest()
    {
        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\b1 request\b/");

        $this->chassis->assertHistoryCount(1);
    }

    public function testAssertHistoryCountFailsDefaultMessageMultipleRequests()
    {
        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\b0 requests\b/");

        $this->chassis->setHistory([
            ['first'], ['second']
        ]);
        $this->chassis->assertHistoryCount(0);
    }

    public function testAssertHistoryCountFailsWithCustomMessage()
    {
        $message = 'my message';

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\b{$message}\b/");

        $this->chassis->assertHistoryCount(3, $message);
    }

    public function testAssertFirstPasses()
    {
        $this->chassis->setHistory([
            ['first']
        ]);

        $this->chassis->assertFirst(function ($e) {
            return $e->withIndexes([0]);
        });
    }

    public function testAssertNotFirstPasses()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->chassis->assertNotFirst(function ($e) {
            return $e->withIndexes([1]);
        });
    }

    public function testAssertFirstFails()
    {
        $this->chassis->setHistory([
            ['first']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\bfirst\b/");

        $this->chassis->assertFirst(function ($e) {
            return $e->withIndexes([]);
        });
    }

    public function testAssertNotFirstFails()
    {
        $this->chassis->setHistory([
            ['first']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\bfirst\b/");
        $this->{self::$regexMethodName}("/\bnot meet\b/");

        $this->chassis->assertNotFirst(function ($e) {
            return $e->withIndexes([0]);
        });
    }

    public function testAssertFirstFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
            ['first']
        ]);

        $m = 'the special message';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($m);

        $this->chassis->assertFirst(function ($e) {
            return $e->withIndexes([]);
        }, $m);
    }

    public function testAssertNotFirstFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
            ['first']
        ]);

        $m = 'A custom message';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($m);

        $this->chassis->assertNotFirst(function ($e) {
            return $e->withIndexes([0]);
        }, $m);
    }

    public function testAssertAllSuccess()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->chassis->assertAll(function ($e) {
            return $e->withIndexes([0,1]);
        });
    }

    public function testAssertAllEmpty()
    {
        $this->expectException(UndefinedIndexException::class);
        $this->{self::$regexMethodName}("/\bempty\b/");
        $this->chassis->assertAll(function ($e) {
            return $e->withIndexes([]);
        });
    }

    public function testAssertAllFailDefaultMessage()
    {
        $this->chassis->setHistory([
           ['first'], ['second']
        ]);

        $this->expectException(AssertionFailedError::class);
        // Should include indexes of failed history items
        $this->{self::$regexMethodName}("/\b[1,2]\b/");
        $this->chassis->assertAll(function ($e) {
            return $e->withIndexes([]);
        });
    }

    public function testAssertAllFailWithCustomMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $message = 'aoiucoewuewknoih';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $this->chassis->assertAll(function ($e) {
            return $e->withIndexes([]);
        }, $message);
    }

    public function testAssertIndexUndefined()
    {
        $this->expectException(UndefinedIndexException::class);
        // Should include the index number of failure
        $this->{self::$regexMethodName}("/\b[7]\b/");
        $this->chassis->assertIndexes([7], function ($e) {});
    }

    public function testAssertLastPasses()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->chassis->assertLast(function ($e) {
            return $e->withIndexes([0]);
        });
    }

    public function testAssertNotLastPasses()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->chassis->assertNotLast(function ($e) {
            return $e->withIndexes([1]);
        });
    }

    public function testAssertLastFailsDefaultMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\blast request\b/");

        $this->chassis->assertLast(function ($e) {
            return $e->withIndexes([]);
        });
    }

    public function testAssertNotLastFailsWithDefaultMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\bdid not\b/");

        $this->chassis->assertNotLast(function ($e) {
            return $e->withIndexes([0]);
        });
    }

    public function testAssertLastFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $message = 'aoweijcemhoiwe';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $this->chassis->assertLast(function ($e) {
            return $e->withIndexes([]);
        }, $message);
    }

    public function testAssertNotLastFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
           ['first'], ['second']
        ]);

        $m = 'Lorem ipsum sal it amet.';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($m);

        $this->chassis->assertNotLast(function ($e) {
            return $e->withIndexes([0]);
        }, $m);
    }

    public function testAssertNonePasses()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->chassis->assertNone(function ($e) {
            return $e->withIndexes([]);
        });
    }

    public function testAssertNoneFailsDefaultMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $this->expectException(AssertionFailedError::class);
        $this->{self::$regexMethodName}("/\b[1]\b/");

        $this->chassis->assertNone(function ($e) {
            return $e->withIndexes([0]);
        });
    }

    public function testAssertNoneFailsWithCustomMessage()
    {
        $this->chassis->setHistory([
            ['first'], ['second']
        ]);

        $message = 'The hills are alive with the sound of music.';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $this->chassis->assertNone(function ($e) {
            return $e;
        }, $message);
    }
}