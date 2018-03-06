<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateUsdPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:prices:usd';

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
        \App\Jobs\UpdateUsdPrices::dispatch();
    }
}