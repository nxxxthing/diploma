<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class PositionReorderTrait
 * @package App\Models\Traits
 */
trait PositionReorderTrait
{
    protected static function booted()
    {
        static::creating(function ($model) {
            $class = get_class($model);

            if (is_null($model->position)) {
                $model->position = $class::max('position') + 1;
                return;
            }

            $position = $class::max('position') + 1;
            $model->position = min($position, $model->position);
        });

        static::updating(function ($model) {
            $class = get_class($model);

            if (is_null($model->position)) {
                $model->position = $class::max('position') + 1;
                return;
            }

            $position = $class::max('position') + 1;
            $model->position = min($position, $model->position);
        });

        static::created(function ($model) {
            $class = get_class($model);

            $class::where('position', '>=', $model->position)
                ->where('id', '<>', $model->id)
                ->incrementQuietly('position');
        });


        static::updated(function ($model) {
            if ($model->isClean('position')) {
                return;
            }

            $class = get_class($model);

            if ($model->getOriginal('position') > $model->position) {
                $class::where('id', '<>', $model->id)
                    ->whereBetween('position', [$model->position, $model->getOriginal('position')])
                    ->incrementQuietly('position');
            } else {
                $class::where('id', '<>', $model->id)
                    ->whereBetween('position', [$model->getOriginal('position'), $model->position])
                    ->decrementQuietly('position');
            }
        });

        static::deleted(function ($model) {
            get_class($model)::where('position', '>', $model->position)
                ->decrementQuietly('position');
        });
    }

    public function scopeIncrementQuietly(Builder $q, string $column, $amount = 1, array $extra = [])
    {
        return static::withoutEvents(function () use ($q, $column, $amount, $extra) {
            return $q->increment($column, $amount, $extra);
        });
    }

    public function scopeDecrementQuietly(Builder $q, string $column, $amount = 1, array $extra = [])
    {
        return static::withoutEvents(function () use ($q, $column, $amount, $extra) {
            return $q->decrement($column, $amount, $extra);
        });
    }
}
