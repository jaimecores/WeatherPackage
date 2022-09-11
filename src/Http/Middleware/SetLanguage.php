<?php

namespace JaimeCores\WeatherPackage\Http\Middleware;

use Closure;
use App;
use Session;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        $availableLangs = ['en', 'es'];
        $userLangs = preg_split('/,|;/', $request->server('HTTP_ACCEPT_LANGUAGE'));

        foreach ($availableLangs as $lang) {
            if(in_array($lang, $userLangs)) {
                App::setLocale($lang);
                break;
            }
        }

        return $next($request);
    }
}