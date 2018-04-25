<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBlocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $first_block;
    protected $last_block;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($first_block, $last_block)
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->first_block = $first_block;
        $this->last_block = $last_block;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $blocks = $this->getBlocks($this->first_block, $this->last_block);

        foreach($blocks as $data)
        {
            $block = $this->updateOrCreateBlock($data);

            $this->processMessages($data['_messages']);
        }
    }

    private function getBlocks($first_block, $last_block)
    {
        $block_indexes = range($first_block, $last_block);

        return $this->counterparty->execute('get_blocks', [
            'block_indexes' => $block_indexes,
        ]);
    }

    private function updateOrCreateBlock($data)
    {
        try {
            $mined_at = \Carbon\Carbon::createFromTimestamp($data['block_time'], 'America/New_York');
        } catch(\Exception $e) {
            $mined_at = \Carbon\Carbon::createFromTimestamp($data['block_time'], 'America/New_York')->addHour();
        }

        return \App\Block::updateOrCreate([
            'block_hash' => $data['block_hash'],
        ],[
            'block_index' => $data['block_index'],
            'block_time' => $data['block_time'],
            'difficulty' => $data['difficulty'],
            'previous_block_hash' => $data['previous_block_hash'],
            'mined_at' => $mined_at,
        ]);
    }

    private function processMessages($messages)
    {
        foreach($messages as $message)
        {
            if('orders' === $message['category'])
            {
                $this->updateOrCreateOrder($message);
            }
            elseif('order_matches' === $message['category'])
            {
                $this->updateOrCreateOrderMatch($message);
            }
        }
    }

    private function updateOrCreateOrder($message)
    {
        if('update' === $message['command'])
        {
            return $this->updateOrder($message);
        }

        return $this->createOrder($message);
    }

    private function updateOrCreateOrderMatch($message)
    {
        if('update' === $message['command'])
        {
            return $this->updateOrderMatch($message);
        }

        return $this->createOrderMatch($message);
    }

    private function createOrder($message)
    {
        $data = $this->getData($message);
        $market = $this->getMarket($data);

        $type = $data['get_asset'] === $market->baseAsset->name ? 'buy' : 'sell';
        $base = 'buy' === $type ? 'get' : 'give';
        $quote = 'sell' === $type ? 'get' : 'give';

        $exchange_rate = $this->getExchangeRate($type, $market->baseAsset, $data[$base.'_quantity'], $market->quoteAsset, $data[$quote.'_quantity']);
        $exchange_rate_usd = $this->getExchangeRateUsd($market->quoteAsset, $exchange_rate, $data['block_index']);

        $order = \App\Order::updateOrCreate([
            'type' => $type,
            'source' => $data['source'],
            'market_id' => $market->id,
            'block_index' => $data['block_index'],
            'expire_index' => $data['expire_index'],
            'tx_index' => $data['tx_index'],
            'tx_hash' => $data['tx_hash'],
            'fee_paid' => $data['fee_provided'],
            'duration' => $data['expiration'],
        ],[
            'status' => $data['status'],
            'base_quantity' => $data[$base.'_quantity'],
            'base_remaining' => $data[$base.'_remaining'],
            'quote_quantity' => $data[$quote.'_quantity'],
            'quote_remaining' => $data[$quote.'_remaining'],
            'exchange_rate' => $exchange_rate,
            'exchange_rate_usd' => $exchange_rate_usd,
        ]);

        \App\Jobs\UpdateMarketSummary::dispatch($order->market);
    }

    private function updateOrder($message)
    {
        $data = $this->getData($message);

        $order = \App\Order::whereTxHash($data['tx_hash'])->first();

        $base = 'buy' === $order->type ? 'get' : 'give';
        $quote = 'sell' === $order->type ? 'get' : 'give';

        if(isset($data['get_remaining']))
        {
            $order->update([
                'status' => $data['status'],
                'base_remaining' => $data[$base.'_remaining'],
                'quote_remaining' => $data[$quote.'_remaining'],
            ]);
        }
        else
        {
            $order->update([
                'status' => $data['status'],
            ]);
        }

        \App\Jobs\UpdateMarketSummary::dispatch($order->market);
    }

    private function createOrderMatch($message)
    {
        $data = $this->getData($message);

        $order = \App\Order::whereTxIndex($data['tx0_index'])->first();
        $match = \App\Order::whereTxIndex($data['tx1_index'])->first();

        $base = 'buy' === $match->type ? 'forward' : 'backward';
        $quote = 'buy' === $match->type ? 'backward' : 'forward';

        $quote_quantity_usd = $this->getQuoteQuantityUsd($order->market->quoteAsset, $data[$quote.'_quantity'], $data['tx1_block_index']);

        $order_match = \App\OrderMatch::firstOrCreate([
            'market_id' => $order->market->id,
            'order_id' => $order->id,
            'order_match_id' => $match->id,
            'block_index' => $data['tx1_block_index'],
            'tx_index' => $data['tx1_index'],
            'status' => $data['status'],
        ],[
            'base_quantity' => $data[$base.'_quantity'],
            'quote_quantity' => $data[$quote.'_quantity'],
            'quote_quantity_usd' => $quote_quantity_usd,
            'exchange_rate_usd' => $order->exchange_rate_usd,
        ]);

        \App\Jobs\UpdateMarketSummary::dispatch($order_match->market);
    }

    private function updateOrderMatch($message)
    {
        $data = $this->getData($message);

        $tx_hash = explode('_', $data['order_match_id'])[0];

        $order_match = \App\OrderMatch::whereHas('order', function ($query) use($tx_hash) {
            $query->where('tx_hash', '=', $tx_hash);
        })->orWhereHas('orderMatch', function ($query) use($tx_hash) {
            $query->where('tx_hash', '=', $tx_hash);
        })->first();

        $order_match->update([
            'status' => $data['status'],
        ]);

        \App\Jobs\UpdateMarketSummary::dispatch($order_match->market);
    }

    private function getData($message)
    {
        return json_decode($message['bindings'], true);
    }

    private function getMarket($data)
    {
        $asset_pair = $this->getAssetPairFromAssets($data['give_asset'], $data['get_asset']);
        $base_asset = \App\Asset::whereName($asset_pair[0])->first();
        $quote_asset = \App\Asset::whereName($asset_pair[1])->first();

        return \App\Market::firstOrCreate([
            'base_asset_id' => $base_asset->id,
            'quote_asset_id' => $quote_asset->id,
        ],[
            'name' => "{$base_asset->display_name}/{$quote_asset->display_name}",
            'slug' => "{$base_asset->display_name}_{$quote_asset->display_name}",
        ]);
    }

    private function getExchangeRate($type, $base_asset, $base_quantity, $quote_asset, $quote_quantity)
    {
        if($base_quantity == 0) return 0;

        $exchange_rate = $quote_quantity / $base_quantity;

        $method = 'buy' == $type ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP;

        if(! $base_asset->divisible && $quote_asset->divisible) $exchange_rate = fromSatoshi($exchange_rate);

        return round($exchange_rate, 8, $method);
    }

    private function getExchangeRateUsd($asset, $exchange_rate, $block_index)
    {
        $block = \App\Block::whereBlockIndex($block_index)->first();

        $price = $asset->histories()->whereType('price')
            ->where('reported_at', 'like', $block->mined_at->toDateString() . '%')
            ->first();

        if(! $price) return null;

        return bcdiv($exchange_rate * $price->value, 100000000, 8);
    }

    private function getQuoteQuantityUsd($asset, $quote_quantity, $block_index)
    {
        if($quote_quantity === 0) return 0;

        $block = \App\Block::whereBlockIndex($block_index)->first();

        $price = $asset->histories()->whereType('price')
            ->where('reported_at', 'like', $block->mined_at->toDateString() . '%')
            ->first();

        if(! $price) return null;

        if($asset->divisible) $quote_quantity = fromSatoshi($quote_quantity);

        return bcdiv($quote_quantity * $price->value, 100000000, 8);
    }

    private function getAssetPairFromAssets($asset1, $asset2)
    {
        foreach($this->getQuoteAssets() as $quote_asset)
        {
            if($asset1 == $quote_asset || $asset2 == $quote_asset)
            {
                return $asset1 == $quote_asset ? [$asset2, $asset1] : [$asset1, $asset2];
            }
        }

        return $asset1 < $asset2 ? [$asset1, $asset2] : [$asset2, $asset1];
    }

    private function getQuoteAssets()
    {
        return ['BTC', 'XCP', 'XBTC', 'SJCX', 'PEPECASH', 'BITCRYSTALS', 'WILLCOIN', 'LTBCOIN', 'FLDC', 'RUSTBITS', 'DATABITS', 'PENISIUM', 'XFCCOIN', 'SWARM', 'CROPS', 'BITCORN'];
    }
}