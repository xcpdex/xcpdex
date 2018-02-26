<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePricesCommand extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Estimate Prices in USD';

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
        $assets = \App\Asset::has('histories')->get();

        foreach($assets as $asset)
        {
            \App\Jobs\UpdatePrices::dispatch($asset);
        }
    }
}