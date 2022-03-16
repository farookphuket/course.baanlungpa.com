<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;
use Eloquent;

class CategorySeeder extends Seeder
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
        $path = 'DB/category_list.sqlite';
        DB::unprepared(file_get_contents($path));
        $this->command->info("Category has been Created!!");
    }
}
