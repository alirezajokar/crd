<?php

namespace Fandoq\Crudgenerator\commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class f4ndoqAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'f4ndoq:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->call('migrate');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error($e->getMessage());
            exit();
        }

        if (\App::VERSION() >= '5.2') {
            $this->info("Generating the authentication scaffolding");
            $this->call('make:auth');
        }

        $this->info("Publishing the assets");
        $this->call('vendor:publish', ['--provider' => 'Fandoq\Crudgenerator\crudgeneratorServiceProvider', '--force' => true]);
//        $this->call('vendor:publish', ['--provider' => 'f4ndoq\crudgenerator\LaravelAdminServiceProvider', '--force' => true]);

        $this->info("Dumping the composer autoload");
        (new Process('composer dump-autoload'))->run();

        $this->info("Migrating the database tables into your application");
        $this->call('migrate');

        $this->info("Adding the routes");

        $routeFile = app_path('Http/routes.php');
        if (\App::VERSION() >= '5.3') {
            $routeFile = base_path('routes/web.php');
        }

        $routes =
            <<<EOD
Route::group(['middleware'=>['auth','admin'],'prefix' => 'admin'],function(){
            
Route::get('/', 'Admin\\AdminController@index');
Route::resource('/roles', 'Admin\\RolesController');
Route::resource('/permissions', 'Admin\\PermissionsController');
Route::resource('/users', 'Admin\\UsersController');
Route::get('/generator', ['uses' => '\Fandoq\Crudgenerator\Controllers\ProcessController@getGenerator']);
Route::post('/generator', ['uses' => '\Fandoq\Crudgenerator\Controllers\ProcessController@postGenerator']);
});
EOD;

        File::append($routeFile, "\n" . $routes);

        $this->info("Overriding the AuthServiceProvider");
        $contents = File::get(__DIR__ . '/../../publish/Providers/AuthServiceProvider.php');
        File::put(app_path('Providers/AuthServiceProvider.php'), $contents);
        $this->info("Add admin user");
        $this->call('db:seed',['--class' => 'addadminUser']);
        $this->info("your admin user is: email: admin@change.me   password: 123456");
        $this->info("Successfully installed Laravel Admin!");
    }
}
