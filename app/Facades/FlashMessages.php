<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FlashMessages
 * @package App\Facades
 */
class FlashMessages extends Facade
{
    
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.messages';
    }
}