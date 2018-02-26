<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePenisium implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $images = [
            'https://penisium.org/wp-content/uploads/2017/10/speen.gif',
            'https://penisium.org/wp-content/uploads/2017/09/mcafeepeen.png',
            'https://penisium.org/wp-content/uploads/2017/09/DORKINS.png',
            'https://penisium.org/wp-content/uploads/2017/09/PEENPANTHER.png',
            'https://penisium.org/wp-content/uploads/2017/09/warholpeen.png',
            'https://penisium.org/wp-content/uploads/2017/08/ramsaybolton.gif',
            'https://penisium.org/wp-content/uploads/2017/08/WHORECRUX.png',
            'https://penisium.org/wp-content/uploads/2017/08/peenocchio.png',
            'https://penisium.org/wp-content/uploads/2017/08/TRIPPEEN.png',
            'https://penisium.org/wp-content/uploads/2017/08/PEGGPLANT.png',
            'https://penisium.org/wp-content/uploads/2017/08/DONGSLOSTMAP.png',
            'https://penisium.org/wp-content/uploads/2017/08/SHLONGDONG.png',
            'https://penisium.org/wp-content/uploads/2017/08/PEENINSULA.png',
            'https://penisium.org/wp-content/uploads/2017/08/ANTONTAUN.png',
            'https://penisium.org/wp-content/uploads/2017/08/MOBYDICK.png',
            'https://penisium.org/wp-content/uploads/2017/08/TAPEMEASURE.png',
            'https://penisium.org/wp-content/uploads/2017/08/PAPERJPEEN.png',
            'https://penisium.org/wp-content/uploads/2017/08/pokepeen.png',
            'https://penisium.org/wp-content/uploads/2017/08/VITALTHICC.png',
            'https://penisium.org/wp-content/uploads/2017/08/PENISIUMLONG.png',
            'https://penisium.org/wp-content/uploads/2017/08/PEENHEAD.png',
            'https://penisium.org/wp-content/uploads/2017/08/drukpakunley.gif',
            'https://penisium.org/wp-content/uploads/2017/08/PEENARCH.gif',
            'https://penisium.org/wp-content/uploads/2017/08/DAVIDSNOSE.png',
            'https://penisium.org/wp-content/uploads/2017/08/PENISQUIRREL.png',
            'https://penisium.org/wp-content/uploads/2017/08/TURBODASNAIL.png',
            'https://penisium.org/wp-content/uploads/2017/08/ORIGINTURBO.png',
            'https://penisium.org/wp-content/uploads/2017/08/NYANPEEN.gif',
            'https://penisium.org/wp-content/uploads/2017/08/PENISIMOON.png',
            'https://penisium.org/wp-content/uploads/2017/08/DICKBUTTS.png',
            'https://penisium.org/wp-content/uploads/2017/08/PEPEDROP.png',
        ];

        foreach($images as $image)
        {
            $chunk = explode('/', $image);
            $chunk = explode('.', end($chunk));
            $card = strtoupper($chunk[0]);

            $asset = \App\Asset::whereName($card)->first();
            if($asset)
            {
                $asset->update([
                    'meta->template' => 'penisium',
                    'meta->asset_url' => 'https://penisium.org/directory/' . $asset->name . '/',
                    'meta->image_url' => $image,
                ]);
            }
        }
    }
}