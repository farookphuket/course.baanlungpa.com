<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Eloquent;
use DB;

class WhatupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Eloquent::unguard();
        $path = 'DB/whatup_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("User has been Created!!");
    }
}
