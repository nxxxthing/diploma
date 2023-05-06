<?php

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title',
        'short_title'
    ];

    protected $fillable = [];

    public function cathedras(): HasMany
    {
        return $this->hasMany(Cathedra::class);
    }
}
