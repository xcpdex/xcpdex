<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBookOfOrbs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $envs = [
            'Spells of Genesis' => ['spells-of-genesis', 'eSog'],
            'Force of Will' => ['force-of-will', 'eFow'],
            'Oasis Mining' => ['oasis-mining', 'eBla'],
            'Skara' => ['skara', 'eSka'],
            'Age of Rust' => ['age-of-rust', 'eRus'],
            'Age of Chains' => ['age-of-chains', 'eAoc'],
            'Sarutobi' => ['sarutobi', 'eSar'],
            'Memorychain' => ['memorychain', 'eMyc'],
            'BitGirls' => ['bitgirls', 'eBtg'],
            'Diecast' => ['diecast', 'eDie'],
        ];

        foreach($envs as $name => $props)
        {
            $file = file_get_contents('https://api.spellsofgenesis.com/orbscenter/?entity=orbs_center&action=getEnvironment&env=' . $props[1] . '&responseType=JSON&apiv=3&apik=18a48545-96cd-4e56-96aa-c8fcae302bfd&mainAddress=empty&targetAddress=empty');
            $api = json_decode($file);

            foreach(reset($api->Environements)->Assets as $card => $data)
            {
                $project = \App\Project::firstOrCreate([
                    'name' => $name,
                    'slug' => $props[0],
                ]);

                $asset = \App\Asset::whereName($data->assetName)->first();

                $project->assets()->syncWithoutDetaching([$asset->id]);

                if(! $asset->image_url)
                {
                    $asset->update([
                        'meta->template' => $project->slug,
                        'meta->image_url' => $data->image,
                    ]);

                    if(isset($data->image))
                    {
                        try
                        {
                            $contents = file_get_contents($data->image);
                            $file_name = substr($data->image, strrpos($data->image, '/') + 1);
                            $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                            if(\Storage::exists('/public/a/images/' . $file_name))
                            {
                                \Storage::delete('/public/a/images/' . $file_name);
                            }
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
}