<?php

namespace App\Console;

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
        Commands\DownloadImages::class,
        Commands\BlockHeightCommand::class,
        Commands\FetchAssetsCommand::class,
        Commands\FetchMarketsCommand::class,
        Commands\UpdateAgeOfChainsCommand::class,
        Commands\UpdateAgeOfRustCommand::class,
        Commands\UpdateAssetsCommand::class,
        Commands\UpdateBlocksCommand::class,
        Commands\UpdateHistoriesCommand::class,
        Commands\UpdateMarketsCommand::class,
        Commands\UpdatePenisiumCommand::class,
        Commands\UpdatePricesCommand::class,
        Commands\UpdateRarePepeCommand::class,
        Commands\UpdateSpellsOfGenesisCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('block:height')
                 ->everyMinute();
        $schedule->command('update:histories')
                 ->hourly();
        $schedule->command('update:blocks')
                 ->daily();
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
