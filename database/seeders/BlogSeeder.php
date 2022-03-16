<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Eloquent;
use DB;

class BlogSeeder extends Seeder
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
        $path = 'DB/blog_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("Blog has been Created!!");
    }
}
