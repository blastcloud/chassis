<?php

namespace tests\Traits;

use BlastCloud\Chassis\Traits\Helpers;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    use Helpers;

    public function testPluckArray()
    {
        $original = [
            [
                'title' => 'first',
                'prop' => 'one'
            ],
            [
                'title' => 'second'
            ],
            [
                'title' => 'third',
                'prop' => 'three'
            ]
        ];

        $results = $this->pluck($original, 'prop');

        $this->assertCount(2, $results);
        $this->assertContains('one', $results);
        $this->assertContains('three', $results);
    }

    public function testPluckObject()
    {
        $original = [
            (object) [
                'title' => 'first',
                'prop' => 'one'
            ],
            (object) [
                'title' => 'second'
            ],
            (object) [
                'title' => 'third',
                'prop' => 'three'
            ]
        ];

        $results = $this->pluck($original, 'prop');

        $this->assertCount(2, $results);
        $this->assertContains('one', $results);
        $this->assertContains('three', $results);
    }

    public function testVerifyFieldsNonExclusive()
    {
        $fields = [
            'first' => 'value',
            'second' => [
                'sub-one',
                'sub-three'
            ],
            'third' => false
        ];

        $this->assertTrue($this->verifyFields($fields, [
            'first' => 'value',
            'second' => [
                'sub-one',
                'sub-two',
                'sub-three'
            ],
            'third' => false,
            'fourth' => true
        ]));

        $this->assertFalse($this->verifyFields($fields, [
            'first' => 'value',
            'second' => 'no-nested',
            'third' => false
        ]));
    }

    public function testVerifyFieldsExclusive()
    {
        $fields = [
            'first' => 'value',
            'second' => [
                'sub-one',
                'sub-three'
            ],
            'third' => false
        ];

        $this->assertFalse($this->verifyFields($fields, [
            'first' => 'value',
            'second' => [
                'sub-one',
                'sub-two',
                'sub-three'
            ],
            'third' => false,
            'fourth' => true
        ], true));
    }
}