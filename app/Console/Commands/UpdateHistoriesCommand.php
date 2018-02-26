<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateHistoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:histories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Price Histories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $asset_tickers = [
            'XCP' => 'XCP',
            'BTC' => 'BTC',
            'PEPECASH' => 'PEPECASH',
            'BITCRYSTALS' => 'BCY',
            'FLDC' => 'FLDC',
            'RUSTBITS' => 'RUSTBITS',
            'NVST' => 'NVST'
        ];

        foreach($asset_tickers as $name => $ticker)
        {
            \App\Jobs\UpdateHistory::dispatch($name, $ticker);
        }
    }
}