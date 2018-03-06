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
            $issuances = $this->getIssuances($this->asset->name);

            $issuance = end($issuances);
            $total_issuance = array_sum(array_column($issuances,'quantity'));
            $is_divisible = $this->find_key_value($issuances, 'divisible', 1);
            $is_locked = $this->find_key_value($issuances, 'locked', 1);

            $this->asset->update([
                'long_name' => $issuance['asset_longname'],
                'description' => str_limit($issuance['description'], 252),
                'issuance' => $total_issuance,
                'divisible' => $is_divisible,
                'locked' => $is_locked,
                'processed' => 1,
            ]);

        } catch(\Exception $e) {
            \Storage::append('error.log', $this->asset->name);
        }
    }

    private function getIssuances($name)
    {
        return $this->counterparty->execute('get_issuances', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $name,
                ],
                [
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);
    }

    private function find_key_value($array, $key, $val)
    {
        foreach($array as $item)
        {
            if(is_array($item) && $this->find_key_value($item, $key, $val)) return true;

            if(isset($item[$key]) && $item[$key] == $val) return true;
        }

        return false;
    }
}
