<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $ticker;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $ticker)
    {
        $this->name = $name;
        $this->ticker = $ticker;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset = \App\Asset::whereName($this->name)->first();

        $data = json_decode(file_get_contents('http://coincap.io/history/' . $this->ticker, true));

        $last = $asset->histories()->orderBy('timestamp', 'desc')->first();

        foreach($data->market_cap as $result)
        {
            if($last && $last->timestamp > ($result[0] / 1000)) continue;

            \App\History::firstOrCreate([
                'asset_id' => $asset->id,
                'type' => 'market_cap',
                'value' => $result[1],
                'timestamp' => $result[0] / 1000,
            ],[
                'reported_at' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000, 'America/New_York'),
            ]);
        }
        foreach($data->price as $result)
        {
            if($last && $last->timestamp > ($result[0] / 1000)) continue;

            \App\History::firstOrCreate([
                'asset_id' => $asset->id,
                'type' => 'price',
                'value' => $result[1] * 100000000,
                'timestamp' => $result[0] / 1000,
            ],[
                'reported_at' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000, 'America/New_York'),
            ]);
        }
        foreach($data->volume as $result)
        {
            if($last && $last->timestamp > ($result[0] / 1000)) continue;

            \App\History::firstOrCreate([
                'asset_id' => $asset->id,
                'type' => 'volume',
                'value' => $result[1],
                'timestamp' => $result[0] / 1000,
            ],[
                'reported_at' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000, 'America/New_York'),
            ]);
        }
    }
}