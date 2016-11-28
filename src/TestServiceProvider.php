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

        //视图
        $this->publishes([
            __DIR__.'/views/lrts' => base_path('resources/views/lrts'),
            __DIR__.'/views/scms' => base_path('resources/views/scms'),
            __DIR__.'/config/filesy.php' => config_path('filesy.php'),
        ]);
        //资源文件
        $this->publishes([
            __DIR__.'/public/assets/bootstrap' => public_path('assets/bootstrap'),
            __DIR__.'/public/assets/css' => public_path('assets/css'),
            __DIR__.'/public/assets/jquery' => public_path('assets/jquery'),
            __DIR__.'/public/assets/sass' => public_path('assets/sass'),
            __DIR__.'/public/assets/scms' => public_path('assets/scms'),
            __DIR__.'/public/assets/web' => public_path('assets/web'),
        ], 'public');

        //控制器和模型
        $this->publishes([
            __DIR__.'/Scms' => app_path('Http/Controllers/Scms'),
            __DIR__.'/Models' => app_path('Models'),
        ]);
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
