<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Controllers\Auth;

use App\Api\v1\Http\Controllers\BaseController;
use App\Api\v1\Http\Requests\Auth\RestorePassword;
use App\Models\User;
use App\Api\v1\Traits\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class RestorePasswordController extends BaseController
{
    use ResetsPasswords;

    public function restore(RestorePassword $request)
    {
        $email = $request->get('email');
        $user = User::whereEmail($email)->first();

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        Log::debug('RestorePasswordController - result', ['$response' => $response]);

        if ($response == Password::PASSWORD_RESET) {
            $token = auth('api')->login($user);

            return $this->respondWithToken($token);
        }

        if ($response == Password::INVALID_USER) {
            return $this->errorWrongArgs('Invalid user');
        }

        if ($response == Password::INVALID_TOKEN) {
            return $this->errorWrongArgs('Invalid token');
        }

        return $this->errorInternalError();
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
    }
}
