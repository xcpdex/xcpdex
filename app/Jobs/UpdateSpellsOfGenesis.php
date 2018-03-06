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
        $project = \App\Project::firstOrCreate([
            'name' => 'Spells of Genesis',
            'slug' => 'spells-of-genesis',
        ]);

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
                $project->assets()->save($asset);

                $image_url = 'https://xcp.cards/images/sogcards/' . $asset->name . '.jpg';

                $asset->update([
                    'meta->template' => 'spells-of-genesis',
                    'meta->image_url' => $image_url,
                    'meta->series' => $data->element,
                    'meta->number' => $data->rarity,
                ]);

                if(isset($image_url))
                {
                    try
                    {
                        $contents = file_get_contents($image_url, false, stream_context_create($context));
                        $file_name = substr($image_url, strrpos($image_url, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        \Storage::put('/public/a/images/' . $file_name, $contents);
                        $asset->update([
                            'image_url' => url('/storage/a/images/' . $file_name)
                        ]);
                    }
                    catch (\Exception $e)
                    {
                    }
                }
            }
        }
    }
}