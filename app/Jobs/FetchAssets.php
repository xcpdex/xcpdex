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
    protected $method;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($method=null)
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $names = $this->counterparty->execute('get_asset_names');

        switch ($this->method) {
            case 'create':
                $this->createAssets($names);
                break;
            case 'update':
                $this->createAssets($names, true);
                break;
            case 'insert':
                $this->insertAssets($names);
                break;
            case 'import':
                $this->importAssets();
                break;
            default:
                $this->createAssets($names);
                break;
        }
    }

    /**
     * Create / Update Assets
     *
     * @return void
     */
    private function createAssets($names, $update=false)
    {
        foreach($names as $name)
        {
            $asset = \App\Asset::firstOrCreate(['name' => $name]);

            if($asset->wasRecentlyCreated && $update)
            {
                \App\Jobs\UpdateAsset::dispatch($asset);
            }
        }
    }

    /**
     * Bulk Insert Asset Names
     *
     * @return void
     */
    private function insertAssets($names)
    {
        $chunks = array_chunk($names, 1000, true);

        foreach($chunks as $chunk)
        {
            foreach($chunk as $name)
            {
                $assets[] = ['name' => $name];
            }

            \App\Asset::insert($assets);

            $assets = [];
        }
    }

    /**
     * Import Assets
     *
     * @return void
     */
    private function importAssets()
    {
        foreach(range('A', 'Z') as $abc)
        {
            $file = fopen(storage_path("app/assets/{$abc}.csv"), 'r');

            while(($line = fgetcsv($file)) !== false)
            {
                $assets[] = [
                    'name' => $line[0],
                    'issuance' => $line[1],
                    'divisible' => $line[2],
                    'locked' => $line[3],
                ];

                if(count($assets) === 100)
                {
                    \App\Asset::insert($assets);

                    $assets = [];
                }
            }

            \App\Asset::insert($assets);

            $assets = [];

            fclose($file);
        }
    }
}