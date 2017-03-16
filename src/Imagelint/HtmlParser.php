<?php
namespace Imagelint;

use Exception;
use voku\helper\HtmlDomParser;

class HtmlParser
{
    const CSSURLREGEX = "|url\\(['\"]*(.*?)['\"]*\\)|i";

    static function parse($input, $base = null) {
        try {
            $html = HtmlDomParser::str_get_html($input);
        } catch(Exception $e) {
            return $input;
        }
        foreach($html->find('img') as $element) {
            $src = $element->src;
            if(!$src = self::getValidCandidate($src, $base)) {
                continue;
            }
            $element->src = Imagelint::get($src);
        }
        foreach($html->find('*[style]') as $element) {
            $element->style = preg_replace_callback(self::CSSURLREGEX,function($matches) use($base) {
                return self::parseCSSUrl($matches, $base);
            },$element->style);
        }

        return (string)$html;
    }

    private static function getValidCandidate($path, $base) {
        if(!in_array(pathinfo($path,PATHINFO_EXTENSION),['jpg','jpeg','png'])) {
            return false;
        }
        if(substr($path,0,1) === '/') {
            $path = $base . $path;
        }
        if(!Imagelint::isValidURL($path)) {
            return false;
        }
        return $path;
    }
    
    private static function parseCSSUrl($matches, $base) {
        if(!$replacement = self::getValidCandidate($matches[1], $base)) {
            $replacement = $matches[1];
        } else {
            $replacement = Imagelint::get($replacement);
        }
        return 'url("' . $replacement . '")';
    }
}