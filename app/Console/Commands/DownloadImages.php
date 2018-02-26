<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $assets = \App\Asset::where('meta->image_url', '!=', null)->get();

        foreach($assets as $asset)
        {
            $url = $asset->meta['image_url'];
            try {
            $contents = file_get_contents($url);
            $file_name = substr($url, strrpos($url, '/') + 1);
            $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
            \Storage::put('/public/a/images/' . $file_name, $contents);
            $asset->update(['meta->image_url' => url('/storage/a/images/' . $file_name)]);
            } catch (\Exception $e) {
            }
        }

        $assets = \App\Asset::where('meta->icon_url', '!=', null)->get();

        foreach($assets as $asset)
        {
            $url = $asset->meta['icon_url'];
            try {
            $contents = file_get_contents($url);
            $file_name = substr($url, strrpos($url, '/') + 1);
            $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
            \Storage::put('/public/a/icons/' . $file_name, $contents);
            $asset->update(['meta->icon_url' => url('/storage/a/icons/' . $file_name)]);
            } catch (\Exception $e) {
            }
        }
    }
}