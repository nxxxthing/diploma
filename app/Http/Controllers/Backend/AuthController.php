<?php

namespace App\Http\Controllers\Backend;

use App\Api\v1\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->email;

        $user = User::where('email', $email)
            ->with('role')->first();

        if (!$user) {
            $errors = new MessageBag(['email' => [__('admin_labels.error_login')]]);

            return Redirect::back()->withErrors($errors);
        }

        $is_valid = Auth::attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ],
            $request->boolean('remember')
        );

        if ($is_valid) {
            return redirect('/');
        }

        $errors = new MessageBag(['email' => [__('admin_labels.error_login')]]);

        return Redirect::back()->withErrors($errors);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
