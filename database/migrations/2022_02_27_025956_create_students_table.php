<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()
                    ->onDelete("cascade");
            $table->foreignId("class_room_id")->constrained()
                    ->onDelete("cascade");

            $table->timestamp("studied_at");
            $table->timestamp("successed_at")->nullable();
            $table->timestamps();
        });


        Schema::create('class_room_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId("class_room_id")->constrained()
                    ->onDelete("cascade");
            $table->foreignId("student_id")->constrained()
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
        Schema::dropIfExists('students');
        Schema::dropIfExists('class_room_student');
    }
}
