<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait WithTypes
 * @package App\Models\Traits
 */
trait WithTypes
{
    
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $type
     *
     * @return Builder
     */
    public function scopeOfType(Builder $builder, string $type): Builder
    {
        return $builder->where($this->getTable().'.type', $this->getTypeIdByKey($type));
    }
    
    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }
    
    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->getTypeKeyById((int) $this->type);
    }
    
    /**
     * @param string $key
     *
     * @return int|null
     */
    public function getTypeIdByKey(string $key)
    {
        foreach ($this->types as $id => $_key) {
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
    public function getTypeKeyById(int $id)
    {
        foreach ($this->types as $_id => $key) {
            if ($_id != $id) {
                continue;
            }
            
            return $key;
        }
        
        return '';
    }
}