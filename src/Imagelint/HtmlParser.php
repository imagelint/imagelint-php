<?php
namespace Imagelint;

use voku\helper\HtmlDomParser;

class HtmlParser
{
    const CSSURLREGEX = "|url\\(['\"]*(.*?)['\"]*\\)|i";

    static function parse($input, $base = null) {
        $html = HtmlDomParser::str_get_html($input);
        foreach($html->find('img') as $element) {
            $src = $element->src;
            if(!in_array(pathinfo($src,PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                continue;
            }
            if(substr($src, 0, 1) === '/') {
                $src = $base . $src;
            }
            if(!Imagelint::isValidURL($src)) {
                continue;
            }
            $element->src = Imagelint::get($src);
        }
        foreach($html->find('*[style]') as $element) {
            $element->style = preg_replace_callback(self::CSSURLREGEX,__CLASS__ . '::parseCSSUrl',$element->style);
        }

        return $html;
    }

    static function parseCSSUrl($matches) {
        return 'url("' . Imagelint::get($matches[1]) . '")';
    }
}