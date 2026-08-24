<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Required for PostgreSQL compatibility on Neon
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            $table->foreignId('parent_question_id')
                ->nullable()
                ->after('sub_topic_id')
                ->constrained('questions')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            $table->dropConstrainedForeignId('parent_question_id');

        });
    }
};