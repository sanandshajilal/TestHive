<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_test_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('email'); 
            $table->string('access_code'); 
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('mock_test_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedInteger('not_attempted')->default(0);
            $table->decimal('total_marks', 5, 2)->default(0);
            $table->integer('remaining_seconds')->nullable(); 
            $table->integer('last_question_number')->default(1); 
            $table->string('status')->default('in_progress'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_test_attempts');
    }
};
