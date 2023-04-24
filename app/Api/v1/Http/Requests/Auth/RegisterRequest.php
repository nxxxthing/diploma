<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:100','unique:users', 'exists:email_confirmations,email'],
            'password' => ['required', 'string', 'min:6','max:255','confirmed'],
        ];
    }
}
