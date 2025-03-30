<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Record;
use App\Models\Reservation;
use App\Observers\RecordObserver;
use App\Observers\ReservationObserver;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Record::observe(RecordObserver::class);
        Reservation::observe(ReservationObserver::class);
    }
}
