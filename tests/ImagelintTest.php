<?php
namespace Imagelint\Tests;

use Imagelint\Imagelint;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Imagelint\Imagelint
 */
class ImagelintTest extends TestCase
{
    public function testHttpURL()
    {
        $this->assertEquals(Imagelint::get('http://example.com/foo/image.jpg'), 'https://a1.imagelint.com/example.com/foo/image.jpg');
    }

    public function testHttpsURL() {
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg'),'https://a1.imagelint.com/example.com/foo/image.jpg');
    }

    public function testFragmentURL() {
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg#xyz'),'https://a1.imagelint.com/example.com/foo/image.jpg');
    }

    public function testParameterURL() {
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg', ['width'=>200]),'https://a1.imagelint.com/example.com/foo/image.jpg?il-width=200');
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg', ['width'=>200,'height'=>200]),'https://a1.imagelint.com/example.com/foo/image.jpg?il-width=200&il-height=200');
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg', ['width'=>200,'height'=>200,'dpr'=>2]),'https://a1.imagelint.com/example.com/foo/image.jpg?il-width=200&il-height=200&il-dpr=2');
        $this->assertEquals(Imagelint::get('https://example.com/foo/image.jpg?a=1', ['width'=>200,'height'=>200,'dpr'=>2]),'https://a1.imagelint.com/example.com/foo/image.jpg?a=1&il-width=200&il-height=200&il-dpr=2');
    }

    public function testAuthURL() {
        $this->assertEquals(Imagelint::get('https://user:password@example.com/foo/image.jpg'),'https://a1.imagelint.com/user:password@example.com/foo/image.jpg');
    }
    
    public function testInvalidURL() {
        if(method_exists($this, 'setExpectedException')) {
            $this->setExpectedException(\InvalidArgumentException::class);
        } else {
            $this->expectException(\InvalidArgumentException::class);
        }
        Imagelint::get('ftp://example.com/foo/image.jpg');
    }

    public function testInvalidURLWithoutProtocol() {
        if(method_exists($this,'setExpectedException')) {
            $this->setExpectedException(\InvalidArgumentException::class);
        } else {
            $this->expectException(\InvalidArgumentException::class);
        }
        $this->assertEquals(Imagelint::get('example.com/foo/image.jpg'),'https://a1.imagelint.com/example.com/foo/image.jpg');
    }
}
