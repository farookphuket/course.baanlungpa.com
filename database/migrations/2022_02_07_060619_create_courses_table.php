<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->string("course_year");
            $table->string("course_term");
            $table->integer("course_id");
            $table->string("course_credit");
            $table->string("course_name");
            $table->string("course_cover")->nullable();
            $table->text("course_excerpt");
            $table->text("course_body");
            $table->integer("course_hours");
            $table->boolean("course_is_free");
            $table->boolean("course_is_public");
            $table->timestamp("released_at")->nullable();
            $table->timestamps();
        });

        Schema::create('class_room_course', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId("class_room_id")->constrained()
                                             ->onDelete("cascade");

            $table->foreignId("course_id")->constrained()
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
        Schema::dropIfExists('courses');
        Schema::dropIfExists('class_room_course');
    }
}
