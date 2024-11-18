<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:send-attendance-reminder')->
        when(function () {
                return now()->endOfMonth()->isToday();
            });
        $schedule->command('send:class-passed-reminders')->everyMinute();
        
        $schedule->command('send:thirty-min-reminders')->everyMinute();

        $schedule->command('send:one-hour-reminders')->hourly();
        $schedule->command('send:fifteen-min-reminders')->everyMinute();

        // Schedule the notifications:send command to run every minute
        $schedule->command('notifications:send')->everyMinute();

        // Schedule the SendPaymentReminders command to run daily at a specific time
        $schedule->command('send:payment-reminders')->dailyAt('10:00'); 
        
        $schedule->command('send:upcoming-class-reminders')->hourlyAt(5);
        $schedule->command('reminder:send-update-class-schedule')->monthlyOn(25, '00:00');
        $schedule->command('reminder:send-make-class-schedule')->monthlyOn(5, '00:00');
        $schedule->command('send:class-reminders')->dailyAt('20:00')->withoutOverlapping()->onOneServer()->runInBackground();
        $schedule->command('send:class-reminders')->dailyAt('18:00'); // Adjust the time as needed
        $schedule->command('send:class-reminders')->hourly();
        $schedule->command('send:invoices')->everyFiveMinutes();
        $schedule->command('inspire')->hourly();
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
