<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Controllers\Auth;

use App\Api\v1\Events\NewUserPasswordRequested;
use App\Api\v1\Http\Controllers\BaseController;
use App\Api\v1\Http\Requests\Auth\ResetPassword;
use App\Api\v1\Traits\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use UnexpectedValueException;

class ResetPasswordController extends BaseController
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(ResetPassword $request)
    {
        try {
            $user = Password::getUser($this->credentials($request));
        } catch (UnexpectedValueException $e) {
            return $this->errorWrongArgs();
        }

        if ($user) {
            $token = Password::createToken($user);

            event(new NewUserPasswordRequested($user, $token));

            return $this->respondWithSuccess();
        }

        return $this->errorWrongArgs();
    }
}
