<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class TablesBuilder
 * @package App\Facades
 */
class TablesBuilder extends Facade
{
    
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tables_builder';
    }
}