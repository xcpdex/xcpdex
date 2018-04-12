<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateFootballCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:football';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Football Coin';

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
        \App\Jobs\UpdateFootballCoin::dispatch();
    }
}