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
        $quote_asset = \App\Asset::whereName('XCP')->first();

        $offset = 0;

        while($offset <= 1200000)
        {
        $buys = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'give_asset',
                    'op'    => '==',
                    'value' => $quote_asset->name,
                ],
            ],
            'offset' => $offset,
        ]);

        $sells = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'get_asset',
                    'op'    => '==',
                    'value' => $quote_asset->name,
                ],
            ],
            'offset' => $offset,
        ]);

        if(0 == count($buys) + count($sells)) break;

        foreach([$buys, $sells] as $orders)
        {
            foreach($orders as $order)
            {
                $asset_name = $quote_asset->name !== $order['get_asset'] ? $order['get_asset'] : $order['give_asset'];
                $base_asset = \App\Asset::whereName($asset_name)->first();

                if(! $base_asset) continue;

                $market = \App\Market::firstOrCreate([
                    'base_asset_id'  => $base_asset->id,
                    'quote_asset_id' => $quote_asset->id,
                ],[
                    'name' => "{$base_asset->name}/{$quote_asset->name}",
                    'slug' => "{$base_asset->name}_{$quote_asset->name}",
                ]);

                if($market->wasRecentlyCreated)
                {
                    \App\Jobs\UpdateMarket::dispatch($market);
                }
            }
        }
        $offset = $offset + 1000;
        }
    }
}