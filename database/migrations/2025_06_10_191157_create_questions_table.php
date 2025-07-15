<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sub_topic_id')->nullable()->constrained()->onDelete('cascade');
    
            $table->enum('question_type', ['mcq', 'multiple_select', 'one_word', 'table_mcq']);
            $table->text('question_text');
    
            $table->json('options')->nullable();         // A/B/C/D options
            $table->json('correct_answers')->nullable(); // stores either 1 or multiple correct answers
            $table->integer('marks')->default(2);
    
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
