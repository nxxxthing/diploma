<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class VisibleTrait
 * @package App\Models\Traits
 */
trait VisibleTrait
{

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeVisible(Builder $query)
    {
        return $query->where($this->getTable().'.status', true);
    }
}
