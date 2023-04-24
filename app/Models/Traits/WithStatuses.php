<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait WithStatuses
 * @package App\Models\Traits
 */
trait WithStatuses
{

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|array                          $status
     *
     * @return Builder
     */
    public function scopeOfStatus(Builder $builder, $status): Builder
    {
        if (is_array($status)) {
            $statuses = [];

            foreach ($status as $_status) {
                $statuses[] = $this->getStatusIdByKey($_status);
            }

            return $builder->whereIn('status', $statuses);
        }

        return $builder->where('status', $this->getStatusIdByKey($status));
    }

    /**
     * @return array
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }

    /**
     * @return array
     */
    public function getStatusesId(): array
    {
        return array_keys($this->statuses);
    }


    /**
     * @return string
     */
    public function getStringStatus(): string
    {
        return $this->getStatusKeyById($this->order_status);
    }

    /**
     * @param string $key
     *
     * @return int|null
     */
    public function getStatusIdByKey(string $key)
    {
        foreach ($this->statuses as $id => $_key) {
            if ($_key != $key) {
                continue;
            }

            return $id;
        }

        return null;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function getStatusKeyById(int $id): string
    {
        foreach ($this->statuses as $_id => $key) {
            if ($_id != $id) {
                continue;
            }

            return $key;
        }

        return '';
    }
}
