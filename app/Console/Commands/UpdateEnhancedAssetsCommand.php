<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateEnhancedAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:enhanced';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Enhanced Assets';

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
        $assets = \App\Asset::where('description', 'like', '%.json')
            ->where('description', 'like', '%/%')
            ->whereEnhanced(0)
            ->whereProcessed(1)
            ->get();

        foreach($assets as $asset)
        {
            \App\Jobs\UpdateEnhancedAsset::dispatch($asset);
        }
    }
}