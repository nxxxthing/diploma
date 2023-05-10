<?php

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title',
    ];
}
