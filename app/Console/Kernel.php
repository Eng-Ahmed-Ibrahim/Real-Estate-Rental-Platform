<?php

namespace App\Console;

use App\Models\CronjobExpression;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:notify-expire-packages')->everySecond();
        // $schedule->command('app:notify-booking-payment')->everySecond();
        // $schedule->command('app:notify-booking-payment-today')->everySecond();
        // $schedule->command('app:notify-booking-payment-yesterday')->everySecond();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
