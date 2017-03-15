<?php

namespace Imagelint\Laravel;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Imagelint\HtmlParser;

class ImagelintMiddleware
{
    public function handle($request,Closure $next) {
        /** @var Response $response */
        $response = $next($request);
        $response->setContent(HtmlParser::parse($response->content(), Config::get('app.url')));
        return $response;
    }
}