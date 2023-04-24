<?php

namespace App\Models;

use App\Models\Traits\PositionSortedTrait;
use App\Models\Traits\VisibleTrait;
use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use Translatable;
    use WithTranslationsTrait;
    use PositionSortedTrait;
    use VisibleTrait;

    protected $translatedAttributes = [
        'title',
        'description',
    ];

    protected $fillable = [
        'slug',
        'image',
        'status',
        'position',
    ];

    public function content(): HasMany
    {
        return $this->hasMany(ModuleContent::class);
    }
}
