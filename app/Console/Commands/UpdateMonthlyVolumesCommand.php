<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMonthlyVolumesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:volumes:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Monthly Volumes';

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
        $markets = \App\Market::has('orderMatches')->get();

        foreach($markets as $market)
        {
            \App\Jobs\UpdateMonthlyVolume::dispatch($market);
        }
    }
}