<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAgeOfRust implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cards = [
            'CRUSADECARD' => [
                'image_url' => 'https://i.imgur.com/xIi5Q51.jpg',
                'number' => '006',
            ],
            'DEVSTATCARD' => [
                'image_url' => 'https://i.imgur.com/WbUZcb5.jpg',
                'number' => '008',
            ],
            'FGTNWASTELND' => [
                'image_url' => 'https://i.imgur.com/hNiDdP8.jpg',
                'number' => '002',
            ],
            'GHOSTPILOT' => [
                'image_url' => 'https://i.imgur.com/xputscl.jpg',
                'number' => '004',
            ],
            'LASTLIGHT' => [
                'image_url' => 'https://i.imgur.com/FMJ7P9Y.jpg',
                'number' => '000-B',
            ],
            'MECHRUNNERS' => [
                'image_url' => 'https://i.imgur.com/Kd0OSDp.jpg',
                'number' => '005',
            ],
            'REVENGECARD' => [
                'image_url' => 'https://i.imgur.com/UxuEhPA.jpg',
                'number' => '007',
            ],
            'ROGUEMECH' => [
                'image_url' => 'https://i.imgur.com/cmRdVE1.jpg',
                'number' => '001',
            ],
            'THEBOUNTY' => [
                'image_url' => 'https://i.imgur.com/jsPNU8b.jpg',
                'number' => '003',
            ],
            'THEORIGIN' => [
                'image_url' => 'https://i.imgur.com/zIO4Wph.jpg',
                'number' => '000-A',
            ],
        ];

        foreach($cards as $card => $data)
        {
            $asset = \App\Asset::whereName($card)->first();
            if($asset)
            {
                $asset->update([
                    'meta->template' => 'age-of-rust',
                    'meta->asset_url' => 'https://www.ageofrust.games/cards/',
                    'meta->image_url' => $data['image_url'],
                    'meta->number' => $data['number'],
                ]);
            }
        }
    }
}