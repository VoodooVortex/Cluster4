<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppUrlConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // ดึงค่า APP_URL จาก env
        $appUrl = config('app.url');

        // ตรวจสอบเงื่อนไข
        if (str_contains($appUrl, 'se.buu.ac.th')) {
            config(['app.cluster_path_prefix' => '/cluster4']);
        } elseif (str_contains($appUrl, 'localhost')) {
            config(['app.cluster_path_prefix' => '']);
        } else {
            config(['app.cluster_path_prefix' => '']); // fallback เผื่อกรณีอื่น
        }
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
