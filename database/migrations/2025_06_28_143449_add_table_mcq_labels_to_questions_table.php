<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Required for PostgreSQL/Neon
    public $withinTransaction = false;

    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('table_mcq_labels')->nullable()->after('correct_answers');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('table_mcq_labels');
        });
    }
};
