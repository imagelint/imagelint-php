<?php

namespace Imagelint\Laravel;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Imagelint\HtmlParser;

class ImagelintMiddleware
{
    public function handle($request,Closure $next) {
        /** @var Response $response */
        $response = $next($request);
        
        // Cache the response so we don't have to parse it everytime
        $cacheKey = md5($response->content() . Config::get('app.url'));
        
        $newContent = Cache::remember($cacheKey, 1440, function() use ($response) {
            return HtmlParser::parse($response->content(),Config::get('app.url'));
        });
        $response->setContent($newContent);
        return $response;
    }
}