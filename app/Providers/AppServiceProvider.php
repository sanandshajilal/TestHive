<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length if needed (optional)
        Schema::defaultStringLength(191);

        // ✅ Disable migration transactions to avoid Neon rollback issues
        Migration::preventTransactions();

        // ✅ Inject Neon endpoint as session variable
        if (config('database.default') === 'pgsql') {
            try {
                $endpoint = env('DB_OPTIONS');
                if ($endpoint) {
                    DB::connection()->getPdo()->exec("SET SESSION neon.endpoint TO '{$endpoint}'");
                }
            } catch (\Exception $e) {
                // You may log this or ignore in local development
                // \Log::warning('Failed to set Neon endpoint: ' . $e->getMessage());
            }
        }
    }
}
