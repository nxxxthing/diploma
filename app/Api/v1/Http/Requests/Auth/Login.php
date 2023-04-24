<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
{
    public function rules(): array
    {
        return [
                'email'    => 'required|exists:users',
                'password' => 'required',
        ];
    }
}
