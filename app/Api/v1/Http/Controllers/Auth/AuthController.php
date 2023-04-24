<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Controllers\Auth;

use App\Api\v1\Http\Controllers\{BaseController};
use App\Api\v1\Http\Requests\Auth\{
    ChangePasswordRequest,
    Login,
    RegisterRequest
};
use App\Api\v1\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\{JWTException};

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            //register user
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorInternalError();
        }

        DB::commit();

        return $this->respondWithSuccess();
    }

    public function login(Login $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->errorValidation([
                'password' => [__('api.wrong_password')]
            ]);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth('api')->user();

        if (! $user) {
            return $this->errorAuth();
        }

        return UserResource::make($user);
    }

    public function refresh()
    {
        try {
            $token = auth('api')->refresh();
        } catch (JWTException $e) {
            return $this->errorWrongArgs();
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('api')->logout();

        return $this->respondWithSuccess('Successfully logged out');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth('api')->user();

        if (! password_verify($request->get('old_password'), $user->password)) {
            return $this->errorWrongArgs();
        }
        $password = bcrypt($request->get('password'));

        $user->update(['password' => $password]);

        return $this->respondWithSuccess();
    }
}
