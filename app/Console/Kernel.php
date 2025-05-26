<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\PackageDurationCommand::class,
        Commands\RemoveDeviceTokens::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('subscriptions:duration')->dailyAt('01:40');
        // $schedule->command('subscriptions:duration')->cron('43 01 * * *');
        // $schedule->command('subscriptions:duration')->everyFiveMinutes();
        // $schedule->command('subscriptions:duration')->everyMinute();
        // $schedule->command('remove:deviceTokens')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
