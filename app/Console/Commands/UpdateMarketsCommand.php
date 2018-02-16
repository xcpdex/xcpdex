<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMarketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:markets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Markets';

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
        $markets = \App\Market::get();

        foreach($markets as $market)
        {
            \App\Jobs\UpdateMarket::dispatch($market);
        }
    }
}