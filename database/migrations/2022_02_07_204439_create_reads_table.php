<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reads', function (Blueprint $table) {
            $table->id();
            $table->string("ip");
            $table->string("os");
            $table->string("device");
            $table->string("browser");
            $table->timestamps();
        });


        Schema::create('blog_read', function (Blueprint $table) {
            $table->id();
            $table->foreignId("blog_id")
                  ->constrained()
                  ->onDelete("cascade");

            $table->foreignId("read_id")
                  ->constrained()
                  ->onDelete("cascade");

            $table->string("ip");
            $table->timestamp("readed_at");
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reads');
    }
}
