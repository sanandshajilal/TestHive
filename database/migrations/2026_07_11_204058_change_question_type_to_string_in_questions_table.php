<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Required for PostgreSQL compatibility on Neon
    public $withinTransaction = false;

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {

            DB::statement("
                ALTER TABLE questions
                MODIFY question_type VARCHAR(50) NOT NULL
            ");

        } elseif (DB::getDriverName() === 'pgsql') {

            DB::statement("
                ALTER TABLE questions
                ALTER COLUMN question_type TYPE VARCHAR(50)
                USING question_type::text
            ");

        }
    }

    public function down(): void
    {
        // Optional - you can leave this empty
        // because converting back to ENUM is rarely needed.
    }
};