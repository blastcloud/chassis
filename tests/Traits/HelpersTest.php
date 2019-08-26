<?php

namespace tests\Traits;

use BlastCloud\Chassis\Helpers\Disposition;
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

    public function testParseHeaderVariables()
    {
        $result = $this->parseHeaderVariables('name', 'Content-Type: text/plain; name="a special value"; another="some value";');
        $this->assertEquals('a special value', $result);

        $res2 = $this->parseHeaderVariables('notHere', 'Content-Length: 42;');
        $this->assertFalse($res2);
    }

    public function testParseMultipartBody()
    {
        $boundary = 'boundary';
        $name = 'first';
        $filename = 'overridden';
        $result = $this->parseMultipartBody('
'.$boundary.'
Content-Disposition: form-data; name="'.$name.'"; filename="'.$filename.'"
Content-Type: text/plain;
Content-Length: 5;

value
'.$boundary.'
shouldnt be in the final', $boundary);

        $this->assertCount(2, $result);

        $obj = $result[0];
        $this->assertInstanceOf(Disposition::class, $obj);
        $this->assertEquals($name, $obj->name);
        $this->assertEquals($filename, $obj->filename);
        $this->assertEquals('value', $obj->contents);
        $this->assertEquals(5, $obj->contentLength);
    }
}