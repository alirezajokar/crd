<?php

namespace Fandoq\Crudgenerator\commands;

use Illuminate\Console\Command;

class sayHello extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fandoq:help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'help for fandoq crud generator';

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
        $this->line("hello! welcome to fandoq crud generator");
        $this->line("after installing the package for install admin panel run command:");
        $this->line("php artisan fandoq:install");
        $this->line("then add below line into kernel in $routeMiddleware");
        $this->line("'admin' => \App\Http\Middleware\CheckAdminAuthenticated::class,");
        $this->line("then run command: php artisan db:seed --class=addadminUser");
        $this->line("http://yourproject.com/admin");
        $this->line("username: admin@change.me");
        $this->line("password: 123456");
    }
}
