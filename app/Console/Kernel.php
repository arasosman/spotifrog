<?php

namespace App\Console;

use App\Helpers\ScheduledTasks;
use App\Http\Controllers\Admin\BotController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){

        })->hourly()->name('test')->withoutOverlapping();

        $schedule->call(function(){
            ScheduledTasks::addRelatedArtist();
        })->everyMinute()->name('addRelatedArtist')->withoutOverlapping();

        $schedule->call(function(){
            ScheduledTasks::checkArtistListIfClaimed();
        })->everyMinute()->name('checkArtistListIfClaimed')->withoutOverlapping();

        $schedule->call(function(){
            ScheduledTasks::fetchDetailInfoForUnclaimedArtist();
        })->everyMinute()->name('fetchDetailInfoForUnclaimedArtist')->withoutOverlapping();

        //crawling:150
        //unclaimed:80
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
