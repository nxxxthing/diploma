<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Exception;

class ApiLangMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if ($request->hasHeader('X-localization')) {
                $locale = $request->header('X-localization');
                App::setLocale($locale);
            }
        } catch (Exception $e) {
                //
        }

        return $next($request);
    }
}
