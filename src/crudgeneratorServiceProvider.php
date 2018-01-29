<?php

namespace Fandoq\Crudgenerator;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class crudgeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                commands\f4ndoqAdminCommand::class,
                commands\maincrud::class,
                commands\CrudMigrationCommand::class,
                commands\CrudControllerCommand::class,
                commands\CrudModelCommand::class,
                commands\CrudViewCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        if (\App::VERSION() <= '5.2') {
            $this->publishes([
                __DIR__ . '/../publish/css/app.css' => public_path('css/app.css'),
            ]);
        }

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/crud-generator/'),
        ]);

//        for admin
        $this->publishes([
            __DIR__ . '/../publish/Middleware/' => app_path('Http/Middleware'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/migrations/' => database_path('migrations'),
        ]);
        $this->publishes([
            __DIR__ . '/../publish/seeds/' => database_path('seeds'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Model/' => app_path(),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/Controllers/' => app_path('Http/Controllers'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/resources/' => base_path('resources'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vendor/laravel-admin'),
        ], 'views');

        $this->loadViewsFrom(__DIR__ . '/views', 'laravel-admin');

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menus.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menus.json')));
            view()->share('laravelAdminMenus', $menus);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('crudgenerator',function(){
            return new crudgenerator;
        });

        $this->mergeConfigFrom(__DIR__ .'/../config/crudgenerator.php','crudgenerator');
    }
}
