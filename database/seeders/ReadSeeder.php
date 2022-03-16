<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;
use Eloquent;

class ReadSeeder extends Seeder
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
        $path = 'DB/read_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("Read has been Created!!");
    }
}
