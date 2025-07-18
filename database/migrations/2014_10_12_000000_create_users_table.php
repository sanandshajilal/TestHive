<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 /** ✅ Add this line to disable transaction for this migration */
    public bool $withinTransaction = false;

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email'); // 🔁 removed ->unique()
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // ✅ Add unique constraint separately
        try {
            DB::statement('ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)');
        } catch (\Exception $e) {
            logger()->error('Could not add unique constraint on users.email: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
