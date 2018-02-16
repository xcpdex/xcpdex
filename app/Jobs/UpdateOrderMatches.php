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
        $offset = 0;

        while($offset <= 1200000)
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
            'offset' => $offset,
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
            'offset' => $offset,
        ]);

        if(0 == count($buys) + count($sells)) break;

        foreach([$buys, $sells] as $order_matches)
        {
            foreach($order_matches as $order_match)
            {
                $order = \App\Order::whereTxIndex($order_match['tx0_index'])->first();
                $match = \App\Order::whereTxIndex($order_match['tx1_index'])->first();

                if(! $match)
                {
                    \Storage::append('tx.log', $order_match['tx1_index']);
                    continue;
                }

                $base = 'buy' == $match->type ? 'forward' : 'backward';
                $quote = 'buy' == $match->type ? 'backward' : 'forward';

                \App\OrderMatch::firstOrCreate([
                    'order_id' => $order->id,
                    'order_match_id' => $match->id,
                    'base_quantity' => $order_match[$base.'_quantity'],
                    'quote_quantity' => $order_match[$quote.'_quantity'],
                ]);
            }
        }
        $offset = $offset + 1000;
        }
    }
}