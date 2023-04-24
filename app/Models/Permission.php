<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use Translatable;
    use WithTranslationsTrait;

    protected $translatedAttributes = [
        'title'
    ];

    protected $fillable = [
        'slug',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
