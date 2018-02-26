<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateRarePepe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = file_get_contents('https://rarepepewallet.com/feed');
        $rares = json_decode($data);

        foreach($rares as $rare => $data)
        {
            $asset = \App\Asset::whereName($rare)->first();
            if($asset)
            {
                $asset->update([
                    'meta->template' => 'rare-pepe',
                    'meta->asset_url' => 'https://pepewisdom.com/' . $asset->name,
                    'meta->image_url' => $data->img_url,
                    'meta->series' => $data->series,
                    'meta->number' => $data->order,
                    'meta->burned' => $data->burned,
                ]);
            }
        }
    }
}
