<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateChartsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:charts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Chart Data';

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
        // $block_index = \Cache::get('block_height') - 10;
        // $markets = \App\Market::whereHas('orderMatches', function ($query) use ($block_index) {
        //       $query->where('block_index', '>', $block_index);
        //    })->get();

        $markets = \App\Market::has('orderMatches')->get();

        foreach($markets as $market)
        {
            \App\Jobs\UpdateChart::dispatch($market);
        }
    }
}