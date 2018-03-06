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
        Commands\DeployCommand::class,
        Commands\BackupAssetsCommand::class,
        Commands\BlockHeightCommand::class,
        Commands\FetchAssetsCommand::class,
        Commands\FetchMarketsCommand::class,
        Commands\UpdateAgeOfChainsCommand::class,
        Commands\UpdateAgeOfRustCommand::class,
        Commands\UpdateAssetsCommand::class,
        Commands\UpdateBlocksCommand::class,
        Commands\UpdateChartsCommand::class,
        Commands\UpdateEnhancedAssetsCommand::class,
        Commands\UpdateHistoriesCommand::class,
        Commands\UpdateMarketsCommand::class,
        Commands\UpdateMarketSummariesCommand::class,
        Commands\UpdatePepeCashHistoryCommand::class,
        Commands\UpdatePenisiumCommand::class,
        Commands\UpdatePricesCommand::class,
        Commands\UpdateRarePepeCommand::class,
        Commands\UpdateSpellsOfGenesisCommand::class,
        Commands\UpdateUsdPricesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('block:height')->everyMinute();
        // $schedule->command('update:rares')->daily();
        // $schedule->command('update:spells')->daily();
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
