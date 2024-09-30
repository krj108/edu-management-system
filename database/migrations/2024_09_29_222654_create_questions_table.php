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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained();  // الاختبار الذي ينتمي إليه السؤال
            $table->string('question_text');  // نص السؤال
            $table->string('option_a');  // الخيار الأول
            $table->string('option_b');  // الخيار الثاني
            $table->string('option_c');  // الخيار الثالث
            $table->string('option_d');  // الخيار الرابع
            $table->string('correct_answer');  // الإجابة الصحيحة
            $table->integer('marks');  // العلامة المخصصة للسؤال
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
