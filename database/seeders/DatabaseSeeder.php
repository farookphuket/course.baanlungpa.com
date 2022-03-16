<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Eloquent;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
             UserSeeder::class,
             RoleSeeder::class,
             VisitorSeeder::class,
             WhatupSeeder::class,

             BlogSeeder::class,
             CategorySeeder::class,
             TagSeeder::class,
             ReadSeeder::class,
             CommentSeeder::class,
             ReplySeeder::class,
        ]);

        // create pip table
        $this->create_pip_table();
    }


        public function create_pip_table(){
        
            /*
             * the pip table have to create after all main table has created
             * */

            /* link user to role */
            Eloquent::unguard();
            $role_file = 'DB/user_role_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Role of User has been Created!!");


            /* Link blog to category */
            Eloquent::unguard();
            $role_file = 'DB/blog_category_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Category link to blog has been Created!!");


            /* Link blog to tag */
            Eloquent::unguard();
            $role_file = 'DB/blog_tag_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Tag link to blog has been Created!!");


            /* Link blog to read */
            Eloquent::unguard();
            $role_file = 'DB/blog_read_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Read link to blog has been Created!!");


            /* Link blog to Comment */
            Eloquent::unguard();
            $role_file = 'DB/blog_comment_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Comment link to blog has been Created!!");

            /* Link blog to reply */
            Eloquent::unguard();
            $role_file = 'DB/blog_reply_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Reply link to blog has been Created!!");


            /* Link reply to comment */
            Eloquent::unguard();
            $role_file = 'DB/reply_comment_link.sqlite';
            DB::unprepared(file_get_contents($role_file));
            $this->command->info("Reply link to Comment has been Created!!");


        }
}
