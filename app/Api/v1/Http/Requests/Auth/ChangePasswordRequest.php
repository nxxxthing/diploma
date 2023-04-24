<?php

declare(strict_types=1);

namespace App\Api\v1\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ];
    }
}
