<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WhereConcatTrait
{
    public function scopeWhereConcat(Builder $builder, $first_field, $second_field, $keyword)
    {
        $builder->whereRaw("concat($first_field,' ',$second_field) LIKE ?", ['%' . $keyword . '%']);
    }
}
