<?php

namespace tests\Helpers;

use BlastCloud\Chassis\Helpers\Disposition;
use PHPUnit\Framework\TestCase;
use BlastCloud\Chassis\Helpers\File;

class FileTest extends TestCase
{
    public function testConstructorAndJsonEncoding()
    {
        $contents = 'some contents of the file';

        $file = new File(
            fopen('data://text/plain,'.$contents, 'r'),
            $filename = 'Fantastic fantastic',
            $type = 'text/plain',
            $headers = ['Host' => 'examples']
        );

        $res = json_decode(json_encode($file), true);

        $this->assertEquals([
            'contents' => $contents,
            'filename' => $filename,
            'contentType' => $type,
            'headers' => $headers
        ], $res);
    }

    /**
     * @dataProvider getDisposition
     * @param Disposition $d
     */
    public function testFactoryAndSetterAndCompare(Disposition $d)
    {
        $file = File::create([
            'contents' => $d->contents,
            'filename' => $d->filename,
            'headers' => ['Host' => 'example.com']
        ]);

        $this->assertTrue($file->compare($d));
    }

    /**
     * @dataProvider getDisposition
     * @param Disposition $d
     */
    public function testCompareFails(Disposition $d)
    {
        $file = new File();
        $file->filename = 'something off';
        $file->contents = 'some contents, yeah!!!';

        $this->assertIsNotBool($file->compare($d));
    }

    /**
     * @dataProvider getDisposition
     * @param Disposition $d
     */
    public function testCompareHeadersFail(Disposition $d)
    {
        $file = new File();
        $file->headers = ['something' => 'else'];

        $this->assertFalse($file->compare($d));
    }

    public function getDisposition()
    {
        return [
            [
                new Disposition('Content-Disposition: form-data; name="file1"; filename="some-funky-name.txt"
Content-Length: 43
Content-Type: text/plain
Host: example.com

some contents, yeah!!!')
            ]
        ];
    }

    public function testNotSettableAttribute()
    {
        $name = 'wacky';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The $name property does not exist on the File object.");

        $file = new File();
        $file->$name = 'anything';
    }
}