<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Meta
 * @package App\Facades
 */
class Meta extends Facade
{
    
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}