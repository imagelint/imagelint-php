<?php
namespace Imagelint;

/**
 * Converts your URLs to Imagelint URLs
 */
class Imagelint
{
    private static $root = 'https://a1.imagelint.com';

    /**
     * Converts your image URL to the Imagelint URL
     * The result when calling the Imagelint URL will be your image, but optimized, compressed and cut to your specified size
     * When you didn't specifiy a size the original size is used.
     * 
     * @param       $url
     * @param array $params [width, height, dpr]
     *
     * @return string
     */
    public static function get($url, $params = array()) {
        if(!self::isValidURL($url)) {
            throw new \InvalidArgumentException('Your URL ' . $url . ' is invalid. Imagelint currently supports http and https URLs.');
        }
        
        return self::buildImagelintURL($url, $params);
    }

    /**
     * Creates the Imagelint URL
     * 
     * @param $url
     * @param $params
     *
     * @return string
     */
    private static function buildImagelintURL($url, $params) {
        return self::$root . '/' . self::sanitize($url) . self::stringifyParams($url, $params); 
    }

    /**
     * @param $url
     * @param $params
     *
     * @return string
     */
    private static function stringifyParams($url, $params) {
        $urlParams = self::getURLParams($params);
        if(!$urlParams) {
            return '';
        }
        $hasQueryParams = parse_url($url,PHP_URL_QUERY) !== null;
        return ($hasQueryParams ? '&':'?') . http_build_query($urlParams);
    }

    /**
     * Returns the params we want to send to Imagelint
     * 
     * @param $params
     *
     * @return array
     */
    private static function getURLParams($params) {
        $mappings = [
            'width' => 'il-width',
            'height' => 'il-height',
            'dpr' => 'il-dpr'
        ];
        $newParams = [];
        foreach($params as $key => $value) {
            if(isset($mappings[$key]) && $value) {
                $newParams[$mappings[$key]] = $value;
            }
        }
        return $newParams;
    }

    /**
     * Removes all parts from the URL which we don't need
     * 
     * @param $url
     *
     * @return string
     */
    private static function sanitize($url) {
        return self::removeProtocol(self::removeFragment($url));
    }
    
    private static function removeFragment($url) {
        if(strpos($url, '#') === false) {
            return $url;
        }
        return strstr($url,'#',true);
    }

    /**
     * Removes the http protocol from the URL
     * 
     * @param $url
     *
     * @return string
     */
    private static function removeProtocol($url) {
        return preg_replace('/^https?:\/\//', '', $url);
    }

    /**
     * Checks if the given URL can be handled by Imagelint
     * 
     * @param $url
     *
     * @return bool
     */
    private static function isValidURL($url) {
        if(filter_var($url,FILTER_VALIDATE_URL) === false) {
            return false;
        }
        if(substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://') {
            return false;
        }
        return true;
    }
}
