<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\CreateUsers::class,
        Commands\FizzBuzz::class,
        Commands\DateTimeDiff::class,
        Commands\DailyTasksCommand::class,
        Commands\WeeklyTasksCommand::class,
        Commands\MonthlyTasksCommand::class,
        Commands\QuarterlyTasksCommand::class,
        Commands\YearlyTasksCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
