<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateOrderMatches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $market;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Market $market)
    {
        $this->market = $market;

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
        $base_asset = $this->market->baseAsset()->first()->name;
        $quote_asset = $this->market->quoteAsset()->first()->name;

        $buy_offset = $this->market->orderMatches()->whereHas('orderMatch', function ($query) {
            $query->where('type', '=', 'buy');
        })->count();

        $sell_offset = $this->market->orderMatches()->whereHas('orderMatch', function ($query) {
            $query->where('type', '=', 'sell');
        })->count();

        while($buy_offset <= 1200000)
        {
            $buys = $this->counterparty->execute('get_order_matches', [
                'filters' => [
                    [
                        'field' => 'forward_asset',
                        'op'    => '==',
                        'value' => $quote_asset,
                    ],[
                        'field' => 'backward_asset',
                        'op'    => '==',
                        'value' => $base_asset,
                    ],
                ],
                'offset' => $buy_offset,
            ]);

            $sells = $this->counterparty->execute('get_order_matches', [
                'filters' => [
                    [
                        'field' => 'forward_asset',
                        'op'    => '==',
                        'value' => $base_asset,
                    ],[
                        'field' => 'backward_asset',
                        'op'    => '==',
                        'value' => $quote_asset,
                    ],
                ],
                'offset' => $sell_offset,
            ]);

            if(0 == count($buys) + count($sells)) break;

            foreach([$buys, $sells] as $order_matches)
            {
                foreach($order_matches as $order_match)
                {
                    $order = \App\Order::whereTxIndex($order_match['tx0_index'])->first();
                    $match = \App\Order::whereTxIndex($order_match['tx1_index'])->first();

                    $base = 'buy' == $match->type ? 'forward' : 'backward';
                    $quote = 'buy' == $match->type ? 'backward' : 'forward';

                    \App\OrderMatch::firstOrCreate([
                        'market_id' => $this->market->id,
                        'order_id' => $order->id,
                        'order_match_id' => $match->id,
                        'tx_index' => $order_match['tx1_index'],
                        'base_quantity' => $order_match[$base.'_quantity'],
                        'quote_quantity' => $order_match[$quote.'_quantity'],
                    ]);

                    \App\Jobs\UpdateBlock::dispatch($order_match['tx1_block_index']);
                }
            }
            $buy_offset = $buy_offset + 1000;
            $sell_offset = $sell_offset + 1000;
        }
    }
}