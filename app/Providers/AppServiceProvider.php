<?php

namespace App\Providers;

use App\Models\Record;
use App\Models\Reservation;
use App\Observers\RecordObserver;
use App\Observers\ReservationObserver;
use Illuminate\Support\ServiceProvider;
use App\Actions\CheckReservationConflict;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CheckReservationConflict::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Record::observe(RecordObserver::class);
        Reservation::observe(ReservationObserver::class);
    }
}
