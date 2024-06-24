<?php

namespace Aman5537jains\AbnCms;


use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class AbnCmsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/abncms.php' => config_path('abncms.php'),
        ],'config');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'AbnCms');

        // $this->publishes([
        //     __DIR__.'/resources/assets' => public_path('abncms'),
        // ], 'assets');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        // $this->publishes([
        //     __DIR__.'/views' => base_path('resources/views/aman'),
        // ]);
        $this->registerDomain();
    }


    protected function registerDomain()
    {

        // if(!app()->runningInConsole())
        // \Aman5537jains\AbnCms\Lib\AbnCms::setDatabase();
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(
        //     __DIR__.'/abncms.php',
        //     'abncms'
        // );
    }
}
