<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAgeOfChainsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:chains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Age of Chains';

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
        \App\Jobs\UpdateAgeOfChains::dispatch();
    }
}