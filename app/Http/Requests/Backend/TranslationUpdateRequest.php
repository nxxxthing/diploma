<?php

declare(strict_types=1);

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class TranslationUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value' => 'min:3|max:100'
        ];
    }
}
