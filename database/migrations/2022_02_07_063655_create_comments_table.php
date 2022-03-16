<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete("cascade");
            $table->string("co_title");
            $table->text("co_body");
            $table->timestamps();
        });


        Schema::create('blog_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId("blog_id")->constrained()->olnDelete("cascade");
            $table->foreignId("comment_id")->constrained()->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('blog_comment');
    }
}
