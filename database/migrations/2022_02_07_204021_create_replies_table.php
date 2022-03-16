<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()
                                        ->onDelete("cascade");
            $table->string("re_title");
            $table->text("re_body");

            $table->timestamps();
        });


        Schema::create('comment_reply', function (Blueprint $table) {
            $table->id();

            $table->foreignId("comment_id")->constrained()
                ->onDelete("cascade");
                  
            $table->foreignId("reply_id")->constrained()
                ->onDelete("cascade");
        });


        Schema::create('blog_reply', function (Blueprint $table) {
            $table->id();
            $table->foreignId("blog_id")
                  ->constrained()->onDelete("cascade");

            $table->foreignId("reply_id")
                  ->constrained()->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
        Schema::dropIfExists('blog_reply');
        Schema::dropIfExists('comment_reply');
    }
}
