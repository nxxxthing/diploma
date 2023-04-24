<?php

namespace App\Providers;

use App\Classes\TablesBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Class TablesBuilderServiceProvider
 * @package App\Providers
 */
class TablesBuilderServiceProvider extends ServiceProvider
{
    
    /**
     * register
     */
    public function register()
    {
        $this->app->bind('tables_builder', TablesBuilder::class);
    }
}