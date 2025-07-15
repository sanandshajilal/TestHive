<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE questions MODIFY question_type ENUM(
            'mcq',
            'multiple_select',
            'one_word',
            'table_mcq',
            'drag_and_drop',
            'dropdown'
        )");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE questions MODIFY question_type ENUM(
            'mcq',
            'multiple_select',
            'one_word',
            'table_mcq'
        )");
    }
    };