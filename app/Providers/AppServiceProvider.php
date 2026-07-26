<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon; // Tambahkan ini juga

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
        // 1. Agar penomoran halaman (pagination) pakai Tailwind
        Paginator::useTailwind();

        // 2. TAMBAHKAN INI: Agar tanggal otomatis jadi Bahasa Indonesia
        Carbon::setLocale('id');
        config(['app.locale' => 'id']);
    }
}