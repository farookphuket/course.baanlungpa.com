<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()
                                        ->onDelete("cascade");
            $table->boolean("allow_edit");
            $table->boolean("is_available");
            $table->timestamp("teached_at");
            $table->timestamps();
        });


        Schema::create('student_teacher', function (Blueprint $table) {
            $table->id();
        
        
            $table->foreignId("student_id")->constrained()
                                        ->onDelete("cascade");
            $table->foreignId("teacher_id")->constrained()
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
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('student_teacher');
    }
}
