<?php

namespace App\Models;

use App\Models\Traits\PositionReorderTrait;
use App\Models\Traits\PositionSortedTrait;
use App\Models\Traits\VisibleTrait;
use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleContent extends Model
{
    use Translatable;
    use WithTranslationsTrait;
    use PositionSortedTrait;
    use VisibleTrait;
    use PositionReorderTrait;

    protected $translatedAttributes = [
        'title',
        'text',
    ];

    protected $fillable = [
        'module_id',
        'status',
        'position',
        'image',
        'video',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
