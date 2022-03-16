<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->string("cat_name");
            $table->string("cat_slug");
            $table->boolean("is_allow_edit")->default(1)->nullable();
            $table->timestamps();
        });


        Schema::create('blog_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId("blog_id")
                  ->constrained()
                  ->onDelete("cascade");

            $table->foreignId("category_id")
                  ->constrained()
                  ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('blog_category');

    }
}
