<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSpellsOfGenesis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $context = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];  

        $data = file_get_contents('https://xcp.cards/api/sog/cards', false, stream_context_create($context));
        $cards = json_decode($data);

        foreach($cards as $card => $data)
        {
            $asset = \App\Asset::whereName($card)->first();
            if($asset)
            {
                $asset->update([
                    'meta->template' => 'spells-of-genesis',
                    'meta->asset_url' => 'https://xcp.cards/sog/cards/' . $asset->name,
                    'meta->image_url' => 'https://xcp.cards/images/sogcards/' . $asset->name . '.jpg',
                    'meta->series' => $data->element,
                    'meta->number' => $data->rarity,
                ]);
            }
        }
    }
}