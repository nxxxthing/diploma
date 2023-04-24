<?php

namespace App\Providers;

use App\Classes\Thumb;
use Illuminate\Support\ServiceProvider;

/**
 * Class ThumbServiceProvider
 * @package App\Providers
 */
class ThumbServiceProvider extends ServiceProvider
{
    
    /**
     * register
     */
    public function register()
    {
        $this->app->bind('thumb', Thumb::class);
    }
}