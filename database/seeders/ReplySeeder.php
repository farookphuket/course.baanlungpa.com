<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Eloquent;
use DB;

class ReplySeeder extends Seeder
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
        $path = 'DB/reply_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("Reply has been Created!!");
    }
}
