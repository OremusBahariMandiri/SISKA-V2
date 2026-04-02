<?php

namespace App\Providers;

use App\Models\Data\DataJenjangKarir;
use App\Models\Data\DataKontrak;
use App\Observers\DataJenjangKarirObserver;
use App\Observers\DataKontrakObserver;
use Illuminate\Support\ServiceProvider;

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
        DataKontrak::observe(DataKontrakObserver::class);
        DataJenjangKarir::observe(DataJenjangKarirObserver::class);
    }
}
