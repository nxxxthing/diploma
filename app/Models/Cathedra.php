<?php

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cathedra extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title',
        'short_title',
    ];

    protected $fillable = [
        'faculty_id',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
