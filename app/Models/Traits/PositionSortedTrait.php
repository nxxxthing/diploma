<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class PositionSortedTrait
 * @package App\Models\Traits
 */
trait PositionSortedTrait
{
    
    /**
     * @param Builder $query
     * @param string  $order
     *
     * @return mixed
     */
    public function scopePositionSorted(Builder $query, $order = 'ASC')
    {
        return $query->orderBy($this->getTable().'.position', $order);
    }
}
