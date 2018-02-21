<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchAssets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset_names = $this->counterparty->execute('get_asset_names');

        foreach($asset_names as $asset_name)
        {
            $asset = \App\Asset::firstOrCreate(['name' => $asset_name]);
        }

        \App\Asset::firstOrCreate([
            'name' => 'XCP',
            'divisible' => 1,
            'processed' => 1,
        ]);

        \App\Asset::firstOrCreate([
            'name' => 'BTC',
            'divisible' => 1,
            'processed' => 1,
        ]);
    }
}
