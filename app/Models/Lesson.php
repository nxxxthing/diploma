<?php

namespace App\Models;

use App\Api\v1\Enums\UserRoles;
use App\Models\Traits\WithTranslationsTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use WithTranslationsTrait;
    use Translatable;

    protected $translatedAttributes = [
        'title',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function studentSchedule()
    {
        $user = auth('web')->user();
        if (! $user?->role?->slug == UserRoles::STUDENT->value) {
            return $this->schedules();
        }
        return $this->schedules()->where('group_id', $user?->group_id);
    }
}
