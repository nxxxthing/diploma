<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Encryption\Encrypter;

class CheckAdminHash
{
    public function handle($request, Closure $next)
    {
        app()->setLocale('en');
        $key = config('app.verify_key');
        $cipher = config('app.code_cipher');

        $parameter = $request->get('adminHash', null);

        if (!$parameter) {
            return response()->json('Admin hash is required', 422);
        }

        $encrypter = new Encrypter($key, $cipher);

        $name = $encrypter->decrypt($parameter, false);

        if (config('app.name') != $name) {
            return response()->json('Invalid admin hash', 403);
        }

        request()->request->remove('adminHash');

        return $next($request);
    }
}
