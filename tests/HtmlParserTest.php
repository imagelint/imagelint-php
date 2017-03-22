<?php
namespace Imagelint\Tests;

use Imagelint\HtmlParser;
use Imagelint\Imagelint;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Imagelint\Imagelint
 */
class HtmlParserTest extends TestCase
{
    public function testThatOnlyHtmlIsParsed()
    {
        $this->assertEquals('example.com/img.jpg', 'example.com/img.jpg');
        $this->assertEquals(json_encode(array('a'=>'example.com/img.jpg')),json_encode(array('a' => 'example.com/img.jpg')));
    }
    
    public function testThatImgTagsAreParsed() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><img src="http://example.com/img.jpg" </body></html>', 'http://example.com');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><img src="https://a1.imagelint.com/example.com/img.jpg"></body></html>
EOT;
        $this->assertEquals($a, $b);
        
    }
    
    public function testThatStyleAttributesAreParsed() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><div style="background: url(http://example.com/img.jpg)"></div></body></html>','http://example.com');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><div style='background: url("https://a1.imagelint.com/example.com/img.jpg")'></div></body></html>
EOT;
        $this->assertEquals($a, $b);
    }

    public function testThatTheBaseIsPrepended() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><img src="/img.jpg" </body></html>', 'http://example.com');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><img src="https://a1.imagelint.com/example.com/img.jpg"></body></html>
EOT;
        $this->assertEquals($a,$b);
    }

    public function testThatInvalidURLsAreNotTouched() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><img src="/img.jpg" </body></html>');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><img src="/img.jpg"></body></html>
EOT;
        $this->assertEquals($a,$b);
    }

    public function testThatURLsFromInvalidHostsAreNotTouched() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><img src="http://example.org/img.jpg" </body></html>', 'http://example.com');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><img src="http://example.org/img.jpg"></body></html>
EOT;
        $this->assertEquals($a,$b);
    }

    public function testThatInvalidStyleAttributesAreNotTouched() {
        $a = HtmlParser::parse('<!DOCTYPE html><html><body><div style="background: url(/img.jpg)"></div></body></html>');
        $b = <<<EOT
<!DOCTYPE html>
<html><body><div style='background: url("/img.jpg")'></div></body></html>
EOT;
        $this->assertEquals($a,$b);

    }
}
