<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Eloquent;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // make user table
        Eloquent::unguard();
        $path = 'DB/user_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("User has been Created!!");

    }
}
