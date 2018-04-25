<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchMarkets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $offset = \App\Order::count();

        while($offset <= 1200000)
        {
            $orders = $this->counterparty->execute('get_orders', [
                'offset' => $offset,
            ]);

            if(0 == count($orders)) break;

            foreach($orders as $order)
            {
                $asset_pair = $this->assets_to_asset_pair($order['get_asset'], $order['give_asset']);

                $base_asset = \App\Asset::whereName($asset_pair[0])->first();
                $quote_asset = \App\Asset::whereName($asset_pair[1])->first();

                if(! $base_asset || ! $quote_asset) continue;

                $market = \App\Market::firstOrCreate([
                    'base_asset_id'  => $base_asset->id,
                    'quote_asset_id' => $quote_asset->id,
                ],[
                    'name' => "{$base_asset->display_name}/{$quote_asset->display_name}",
                    'slug' => "{$base_asset->display_name}_{$quote_asset->display_name}",
                ]);

                if($market->wasRecentlyCreated)
                {
                    \App\Jobs\UpdateMarket::dispatch($market);
                }
            }

            $offset = $offset + 1000;
        }
    }

    private function assets_to_asset_pair($asset1, $asset2)
    {
        foreach($this->quote_assets() as $quote_asset)
        {
            if($asset1 == $quote_asset || $asset2 == $quote_asset)
            {
                return $asset1 == $quote_asset ? [$asset2, $asset1] : [$asset1, $asset2];
            }
        }

        return $asset1 < $asset2 ? [$asset1, $asset2] : [$asset2, $asset1];
    }

    private function quote_assets()
    {
        return ['BTC', 'XCP', 'XBTC', 'SJCX', 'PEPECASH', 'BITCRYSTALS', 'WILLCOIN', 'FLDC', 'LTBCOIN', 'RUSTBITS', 'DATABITS', 'PENISIUM', 'XFCCOIN'];
    }
}