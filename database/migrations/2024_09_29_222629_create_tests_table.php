<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('subject_id')->constrained();  // المادة التي ينتمي إليها الاختبار
            $table->foreignId('class_id')->constrained();    // الصف الذي ينتمي إليه الاختبار
            $table->foreignId('room_id')->constrained();     // الغرفة التي ينتمي إليها الاختبار
            $table->foreignId('teacher_id')->constrained('users');  // المدرس الذي أنشأ الاختبار
            $table->integer('total_marks');  // العلامة الكاملة للاختبار
            $table->integer('passing_marks');  // علامة النجاح
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
