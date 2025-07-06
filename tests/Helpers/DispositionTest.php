<?php

namespace Tests\Helpers;

use BlastCloud\Chassis\Helpers\Disposition;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BlastCloud\Chassis\Helpers\Disposition
 */
#[CoversTrait(Disposition::class)]
class DispositionTest extends TestCase
{
    public function testSimpleValue()
    {
        $d = new Disposition('Content-Disposition: form-data; name="first"
Content-Type: text/plain
Content-Length: 5
Host: example.com

value');

        $this->assertEquals('first', $d->name);
        $this->assertEquals(5, $d->contentLength);
        $this->assertFalse($d->isFile());
        $this->assertNull($d->filename);
        $this->assertEquals('text/plain', $d->contentType);
        $this->assertEquals('value', $d->contents);
        $this->assertEquals(['Host' => 'example.com'], $d->headers);
    }

    public function testFile()
    {
        $d = new Disposition('Content-Disposition: form-data; name="file1"; filename="spikity-spockity.txt"
Content-Length: 43
Content-Type: text/plain

This is the test file. A simple, text file.');

        $this->assertTrue($d->isFile());
        $this->assertEquals('spikity-spockity.txt', $d->filename);
        $this->assertEquals('This is the test file. A simple, text file.', $d->contents);
    }
}