<?php

namespace Huwei1994\Test4;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }
        $this->loadViewsFrom(__DIR__.'/views/lrts/admin', 'test4');
        $this->loadViewsFrom(__DIR__.'/views/scms/admin', 'test4');
        $this->loadViewsFrom(__DIR__.'/views/scms/api', 'test4');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/lrts'),
            __DIR__.'/views' => base_path('resources/views/scms/admin'),
            __DIR__.'/views' => base_path('resources/views/scms/api'),
            __DIR__.'/config/filesy.php' => config_path('filesy.php'),

        ]);
        $this->publishes([
            __DIR__.'/public/assets' => public_path('assets/bootstrap/css'),
            __DIR__.'/public/assets' => public_path('assets/bootstrap/fonts'),
            __DIR__.'/public/assets' => public_path('assets/css'),
            __DIR__.'/public/assets' => public_path('assets/jquery/plugin/fileUploadJs'),
            __DIR__.'/public/assets' => public_path('assets/jquery/plugin/timeConversion'),
            __DIR__.'/public/assets' => public_path('assets/jquery'),
            __DIR__.'/public/assets' => public_path('assets/sass'),
            __DIR__.'/public/assets' => public_path('assets/scms/image/backimage'),
            __DIR__.'/public/assets' => public_path('assets/scms/image/uploads/bigimg'),
            __DIR__.'/public/assets' => public_path('assets/scms/image/uploads/smallimg'),
            __DIR__.'/public/assets' => public_path('assets/web/css'),
            __DIR__.'/public/assets' => public_path('assets/web/html'),
            __DIR__.'/public/assets' => public_path('assets/web/images'),
            __DIR__.'/public/assets' => public_path('assets/web/js'),

        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
