<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")
                  ->constrained()->onDelete("cascade");
            $table->string("d_title");
            $table->string("d_slug");
            $table->boolean("d_is_public")->default(1);
            $table->text("d_body");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_pages');
    }
}
