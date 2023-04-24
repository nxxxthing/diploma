<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Translation model
 * @package App\Models
 */
class Translation extends Model
{

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $group
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfTranslatedGroup($query, $group)
    {
        return $query->where('group', $group)->whereNotNull('value');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $ordered
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByGroupKeys($query, $ordered)
    {
        if ($ordered) {
            $query->orderBy('group')->orderBy('key');
        }

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectDistinctGroup($query)
    {
        $select = '';

        switch (DB::getDriverName()) {
            case 'mysql':
                $select = 'DISTINCT `group`';
                break;
            default:
                $select = 'DISTINCT "group"';
                break;
        }

        return $query->select(DB::raw($select));
    }

    public function getFullIdAttribute(): string
    {
        return $this->group . '_' . $this->locale . '_' . $this->key;
    }
}
