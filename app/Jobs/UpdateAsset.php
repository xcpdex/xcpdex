<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAsset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Asset $asset)
    {
        $this->asset = $asset;
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $issuances = $this->counterparty->execute('get_issuances', [
                'filters' => [
                    [
                        'field' => 'asset',
                        'op'    => '==',
                        'value' => $this->asset->name,
                    ],
                    [
                        'field' => 'status',
                        'op'    => '==',
                        'value' => 'valid',
                    ],
                ],
            ]);
        }
        catch(\Exception $e)
        {
            \Storage::append('error.log', $this->asset->name);

            return true;
        }

        $issuance = end($issuances);
        $total_issuance = array_sum(array_column($issuances,'quantity'));

        if($this->asset->processed && $this->asset->locked)
        {
            $this->asset->update([
                'description' => str_limit($issuance['description'], 252),
            ]);
        }
        elseif($this->asset->processed && isset($issuance['locked']))
        {
            $this->asset->update([
                'description' => str_limit($issuance['description'], 252),
                'issuance' => $total_issuance,
                'locked' => $issuance['locked'],
            ]);
        }
        elseif(isset($issuance['locked']))
        {
            $this->asset->update([
                'long_name' => $issuance['asset_longname'],
                'description' => str_limit($issuance['description'], 252),
                'issuance' => $total_issuance,
                'divisible' => $issuance['divisible'],
                'locked' => $issuance['locked'],
                'processed' => 1,
            ]);
        }
    }
}
