<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateFootballCoin implements ShouldQueue
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
            'name' => 'Football Coin',
            'slug' => 'football-coin',
        ]);

        $context = [
            'http' => [
                'method' => 'POST',
                'header' => 'XFC-AKEY: ' . env('FOOTBALL_COIN_API_KEY'),
            ],
        ];  

        $pages = range(1,10);

        foreach($pages as $page)
        {

        $data = file_get_contents('https://game.footballcoin.io/api/wallet/getPlayerAssetList/' . $page . '/100', false, stream_context_create($context));
        $data = json_decode($data);

        foreach($data->params->players as $player)
        {
            $asset = \App\Asset::whereName($player->cp_asset)->first();

            if($asset)
            {
                $project->assets()->syncWithoutDetaching([$asset->id]);

                $asset->update([
                    'meta->template' => 'football-coin',
                    'meta->image_url' => $player->cardPictureId->big_url,
                    'meta->icon_url' => $player->team_logo,
                    'meta->team' => $player->team_name,
                    'meta->name' => $player->name,
                    'meta->series' => $player->league_name,
                ]);

                if(! $asset->image_url)
                {
                    try
                    {
                        $contents = file_get_contents($player->cardPictureId->big_url);
                        $file_name = substr($player->cardPictureId->big_url, strrpos($player->cardPictureId->big_url, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        \Storage::put('/public/a/images/' . $file_name, $contents);
                        $asset->update([
                            'image_url' => url('/storage/a/images/' . $file_name)
                        ]);
                        $contents = file_get_contents($player->team_logo);
                        $file_name = substr($player->team_logo, strrpos($player->team_logo, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        \Storage::put('/public/a/icons/' . $file_name, $contents);
                        $asset->update([
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

        $data = file_get_contents('https://game.footballcoin.io/api/wallet/getVenuesAssetList/' . $page . '/100', false, stream_context_create($context));
        $data = json_decode($data);

        foreach($data->params->venues as $venue)
        {
            $asset = \App\Asset::whereName($venue->cp_asset)->first();

            if($asset)
            {
                $project->assets()->syncWithoutDetaching([$asset->id]);

                $asset->update([
                    'meta->template' => 'football-coin',
                    'meta->image_url' => $venue->cardPictureId->big_url,
                    'meta->icon_url' => $venue->team_logo,
                    'meta->team' => $venue->team_name,
                    'meta->name' => $venue->name,
                    'meta->series' => $venue->league_name,
                ]);

                if(! $asset->image_url)
                {
                    try
                    {
                        $contents = file_get_contents($venue->cardPictureId->big_url);
                        $file_name = substr($venue->cardPictureId->big_url, strrpos($venue->cardPictureId->big_url, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        \Storage::put('/public/a/images/' . $file_name, $contents);
                        $asset->update([
                            'image_url' => url('/storage/a/images/' . $file_name)
                        ]);
                        $contents = file_get_contents($venue->team_logo);
                        $file_name = substr($venue->team_logo, strrpos($venue->team_logo, '/') + 1);
                        $file_name = str_replace(explode('.', $file_name)[0], $asset->name, $file_name);
                        \Storage::put('/public/a/icons/' . $file_name, $contents);
                        $asset->update([
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
}