<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // For Neon/PostgreSQL
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('batch_id');

            $table->foreign('batch_id')
                ->references('id')
                ->on('batches')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('email');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['batch_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};