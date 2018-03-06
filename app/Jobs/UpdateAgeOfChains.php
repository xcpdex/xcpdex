<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAgeOfChains implements ShouldQueue
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
            'name' => 'Age of Chains',
            'slug' => 'age-of-chains',
        ]);

        $cards = [
            'TRUTHFINDER' => [
                '013',
                '2',
                0,
                'https://www.ageofchains.com/portfolio/skai-finder-truth-card-013/',
                'https://www.ageofchains.com/wp-content/uploads/2017/11/013web.jpg',
            ],
            'SCOUTCARD' => [
                '012',
                '2',
                0,
                'https://www.ageofchains.com/portfolio/invasion-scout-card-012/',
                'https://www.ageofchains.com/wp-content/uploads/2017/11/012web.jpg',
            ],
            'QUARKSCARD' => [
                '011',
                '2',
                0,
                'https://www.ageofchains.com/portfolio/quarks-black-hole-mastercard-010/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/Dark-Black-Hole-Master-Med-Sub.jpg',
            ],
            'RUNEWIZARD' => [
                '010',
                '2',
                0,
                'https://www.ageofchains.com/portfolio/shah-rune-wizard-card-010/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/Shaq-Rune-Wizard-Card-010.jpg',
            ],
            'HARVESTER' => [
                '009',
                '2',
                0,
                'https://www.ageofchains.com/portfolio/battlefield-harvester-card-009/',
                'https://www.ageofchains.com/wp-content/uploads/2017/08/009-003.jpg',
            ],
            'QORACARD' => [
                '008',
                '1',
                1910,
                'https://www.ageofchains.com/portfolio/starjumper-hope-of-qora-card-008/',
                'https://www.ageofchains.com/wp-content/uploads/2017/08/008-01.jpg',
            ],
            'MYRIADCARD' => [
                '007',
                '1',
                34790,
                'https://www.ageofchains.com/portfolio/awakening-of-myriad-card-007/',
                'https://www.ageofchains.com/wp-content/uploads/2017/08/007-01.jpg',
            ],
            'GALAXYBOARD' => [
                '006',
                '1',
                268,
                'https://www.ageofchains.com/portfolio/galaxy-board-card-005/',
                'https://www.ageofchains.com/wp-content/uploads/2017/08/006-01-1.jpg',
            ],
            'METEORABOARD' => [
                '005',
                '1',
                268,
                'https://www.ageofchains.com/portfolio/meteora/',
                'https://www.ageofchains.com/wp-content/uploads/2017/08/004-01-1.jpg',
            ],
            'ELECTRUMCARD' => [
                '004',
                '1',
                1614,
                'https://www.ageofchains.com/portfolio/electrum/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/004-01-1.jpg',
            ],
            'MONEROCARD' => [
                '003',
                '1',
                544,
                'https://www.ageofchains.com/portfolio/lourn-hidden-defender-of-monero/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/003-01.jpg',
            ],
            'TAUCARD' => [
                '002',
                '1',
                166,
                'https://www.ageofchains.com/portfolio/tautimetraveller/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/002-012.jpg',
            ],
            'GUARDIANCARD' => [
                '001',
                '1',
                0,
                'https://www.ageofchains.com/portfolio/guardiancard/',
                'https://www.ageofchains.com/wp-content/uploads/2016/08/Woodcoins-Guardian-Card-001.jpg',
            ],
        ];

        foreach($cards as $card => $data)
        {
            $asset = \App\Asset::whereNull('image_url')->whereName($card)->first();
            if($asset)
            {
                $project->assets()->syncWithoutDetaching([$asset->id]);

                $asset->update([
                    'meta->template' => 'age-of-chains',
                    'meta->number' => $data[0],
                    'meta->series' => $data[1],
                    'meta->burned' => $data[2],
                    'meta->asset_url' => $data[3],
                    'meta->image_url' => $data[4],
                ]);

                if(isset($data[4]))
                {
                    try
                    {
                        $contents = file_get_contents($data[4]);
                        $file_name = substr($data[4], strrpos($data[4], '/') + 1);
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