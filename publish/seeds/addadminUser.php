<?php

use Illuminate\Database\Seeder;

class addadminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'=>'admin',
            'email'=>'admin@change.me',
            'password'=>bcrypt('123456'),
            'level'=>'admin'
        ]);
    }
}
