<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherRoomTable extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_room', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); 
            $table->unsignedBigInteger('room_id'); 
            $table->unsignedBigInteger('subject_id'); 
            $table->timestamps();

       
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_room');
    }
}
