<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Assets';

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
        foreach(range('A', 'Z') as $abc)
        {
            $assets = \App\Asset::where('name', 'like', $abc . '%')->whereProcessed(1)->get();

            // For Bootstrapping
            foreach($assets as $asset)
            {
                \Storage::append("/assets/{$abc}.csv", "{$asset->name}, {$asset->issuance}, {$asset->divisible}, {$asset->locked}");
            }
        }
    }
}
