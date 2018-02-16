<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Assets';

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
        \App\Jobs\FetchAssets::dispatch();
    }
}
