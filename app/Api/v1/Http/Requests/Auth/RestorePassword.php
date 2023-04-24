<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RestorePassword extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email', 'exists:users'],
            'password' => ['required', 'confirmed', 'min:6']
        ];
    }
}
