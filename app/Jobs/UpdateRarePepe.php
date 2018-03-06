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
        $project = \App\Project::firstOrCreate([
            'name' => 'Rare Pepe',
            'slug' => 'rare-pepe',
        ]);

        $data = file_get_contents('https://rarepepewallet.com/feed');
        $rares = json_decode($data);

        foreach($rares as $rare => $data)
        {
            $asset = \App\Asset::whereName($rare)->first();
            if($asset)
            {
                $project->assets()->syncWithoutDetaching([$asset->id]);

                $asset->update([
                    'meta->template' => 'rare-pepe',
                    'meta->asset_url' => 'https://pepewisdom.com/' . $asset->name,
                    'meta->image_url' => $data->img_url,
                    'meta->series' => $data->series,
                    'meta->number' => $data->order,
                    'meta->burned' => $data->burned,
                ]);

                if(isset($data->img_url))
                {
                    try
                    {
                        $contents = file_get_contents($data->img_url);
                        $file_name = substr($data->img_url, strrpos($data->img_url, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        $file = '/public/a/images/' . $file_name;
                        if (\Storage::exists($file)) {
                            \Storage::delete($file);
                        }
                        \Storage::put($file, $contents);
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
