<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Eloquent;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // make roles table
        Eloquent::unguard();
        $path = 'DB/role_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("Role has been Created!!");
    }
}
