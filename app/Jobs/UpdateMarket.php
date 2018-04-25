<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateMarket implements ShouldQueue
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
        $base_asset = $this->market->baseAsset()->first();
        $quote_asset = $this->market->quoteAsset()->first();

        $buy_offset = $this->market->orders()
            ->where('type', '=', 'buy')
            ->count();

        if($buy_offset) $buy_offset = $buy_offset - 10000 > 0 ? $buy_offset - 10000 : 0;

        $sell_offset = $this->market->orders()
            ->where('type', '=', 'sell')
            ->count();

        if($sell_offset) $sell_offset = $sell_offset - 10000 > 0 ? $sell_offset - 10000 : 0;

        while($buy_offset <= 1200000)
        {
            $buys = $this->counterparty->execute('get_orders', [
                'filters' => [
                    [
                        'field' => 'give_asset',
                        'op'    => '==',
                        'value' => $quote_asset->name,
                    ],[
                        'field' => 'get_asset',
                        'op'    => '==',
                        'value' => $base_asset->name,
                    ],
                ],
                'offset' => $buy_offset,
            ]);

            $sells = $this->counterparty->execute('get_orders', [
                'filters' => [
                    [
                        'field' => 'give_asset',
                        'op'    => '==',
                        'value' => $base_asset->name,
                    ],[
                        'field' => 'get_asset',
                        'op'    => '==',
                        'value' => $quote_asset->name,
                    ],
                ],
                'offset' => $sell_offset,
            ]);

            if(0 == count($buys) + count($sells)) break;

            foreach([$buys, $sells] as $orders)
            {
                foreach($orders as $order)
                {
                    $type = $order['get_asset'] == $base_asset->name ? 'buy' : 'sell';
                    $base = 'buy' == $type ? 'get' : 'give';
                    $quote = 'sell' == $type ? 'get' : 'give';

                    \App\Order::updateOrCreate([
                        'type' => $type,
                        'source' => $order['source'],
                        'market_id' => $this->market->id,
                        'block_index' => $order['block_index'],
                        'expire_index' => $order['expire_index'],
                        'tx_index' => $order['tx_index'],
                        'tx_hash' => $order['tx_hash'],
                        'fee_paid' => $order['fee_provided'],
                        'duration' => $order['expiration'],
                    ],[
                        'status' => $order['status'],
                        'base_quantity' => $order[$base.'_quantity'],
                        'base_remaining' => $order[$base.'_remaining'],
                        'quote_quantity' => $order[$quote.'_quantity'],
                        'quote_remaining' => $order[$quote.'_remaining'],
                        'exchange_rate' => $this->getExchangeRate($type, $base_asset, $order[$base.'_quantity'], $quote_asset, $order[$quote.'_quantity']),
                    ]);

                    \App\Jobs\UpdateBlock::dispatch($order['block_index']);
                }
            }
            $buy_offset = $buy_offset + 1000;
            $sell_offset = $sell_offset + 1000;
        }

        \App\Jobs\UpdateOrderMatches::dispatch($this->market);
    }

    private function getExchangeRate($type, $base_asset, $base_quantity, $quote_asset, $quote_quantity)
    {
        if($base_quantity == 0) return 0;

        $exchange_rate = $quote_quantity / $base_quantity;

        $method = 'buy' == $type ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP;

        if(! $base_asset->divisible && $quote_asset->divisible) $exchange_rate = fromSatoshi($exchange_rate);

        return round($exchange_rate, 8, $method);
    }
}