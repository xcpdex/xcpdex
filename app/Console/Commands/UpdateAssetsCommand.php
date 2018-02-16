<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Assets';

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
        $assets = \App\Asset::has('markets')->where('locked', '=', 0)->orWhere('processed', '=', 0)->get();

        foreach($assets as $asset)
        {
            \App\Jobs\UpdateAsset::dispatch($asset);
        }
    }
}