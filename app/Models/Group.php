<?php

namespace App\Models;

use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title'
    ];

    protected $fillable = [
        'cathedra_id',
    ];

    public function cathedra(): BelongsTo
    {
        return $this->belongsTo(Cathedra::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
