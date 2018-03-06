<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateEnhancedAsset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Asset $asset)
    {
        $this->asset = $asset;
    }

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

        if('http' != substr($this->asset->description, 0, 4))
        {
            $url_http = 'http://' . $this->asset->description;
            $url_https = 'https://' . $this->asset->description;

            try {
                $data = file_get_contents($url_http, false, stream_context_create($context));
            } catch(\Exception $e) {
                try {
                    $data = file_get_contents($url_https, false, stream_context_create($context));
                } catch(\Exception $e) {
                }
            }
        }
        else
        {
            try {
                $data = file_get_contents($this->asset->description, false, stream_context_create($context));
            } catch(\Exception $e) {
            }
        }

        if(isset($data))
        {
            $data = json_decode($data);

            $this->asset->update([
                'meta->asset_url' => isset($data->website) ? $data->website : null,
                'meta->description' => isset($data->description) ? $data->description : null,
                'meta->icon_url' => isset($data->image) ? $data->image : null,
                'enhanced' => 1,
            ]);

            if(isset($data->image))
            {
                try
                {
                    $contents = file_get_contents($data->image);
                    $file_name = substr($data->image, strrpos($data->image, '/') + 1);
                    $file_name = str_replace(explode('.', $file_name)[0], $this->asset->name, $file_name);
                    \Storage::put('/public/a/icons/' . $file_name, $contents);
                    $this->asset->update([
                        'icon_url' => url('/storage/a/icons/' . $file_name)
                    ]);
                }
                catch (\Exception $e)
                {
                }
            }
        }
    }
}