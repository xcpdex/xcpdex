<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePepeCashHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset = \App\Asset::whereName('PEPECASH')->first();

        $file = fopen(storage_path('app/pepecash-price.csv'), 'r');

        while(($line = fgetcsv($file)) !== false)
        {
            $date = \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateString();

            if(! $asset->histories()->whereType('price')->where('reported_at', 'like', $date . '%')->exists())
            {
                \App\History::firstOrCreate([
                    'asset_id' => $asset->id,
                    'type' => 'price',
                    'value' => $line[1] * 100,
                    'timestamp' => \Carbon\Carbon::parse($line[0], 'America/New_York')->timestamp,
                ],[
                    'reported_at' => \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateTimeString(),
                ]);
            }
        }

        fclose($file);

        $file = fopen(storage_path('app/pepecash-volume.csv'), 'r');

        while(($line = fgetcsv($file)) !== false)
        {
            $date = \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateString();

            if(! \App\History::whereType('volume')->where('reported_at', 'like', $date . '%')->exists())
            {
                \App\History::firstOrCreate([
                    'asset_id' => $asset->id,
                    'type' => 'volume',
                    'value' => $line[1],
                    'timestamp' => \Carbon\Carbon::parse($line[0], 'America/New_York')->timestamp,
                ],[
                    'reported_at' => \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateTimeString(),
                ]);
            }
        }

        fclose($file);

        $file = fopen(storage_path('app/pepecash-market-cap.csv'), 'r');

        while(($line = fgetcsv($file)) !== false)
        {
            $date = \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateString();

            if(! \App\History::whereType('market_cap')->where('reported_at', 'like', $date . '%')->exists())
            {
                \App\History::firstOrCreate([
                    'asset_id' => $asset->id,
                    'type' => 'market_cap',
                    'value' => $line[1],
                    'timestamp' => \Carbon\Carbon::parse($line[0], 'America/New_York')->timestamp,
                ],[
                    'reported_at' => \Carbon\Carbon::parse($line[0], 'America/New_York')->toDateTimeString(),
                ]);
            }
        }

        fclose($file);
    }
}