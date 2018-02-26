<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Asset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = $this->asset->quoteMarketsOrders()->whereExchangeRateUsd(0)->get();

        foreach($orders as $order)
        {
            $price = $this->asset->histories()
                ->whereType('price')
                ->where('timestamp', 'like', $order->block->block_time->toDateString() . '%')
                ->first();

            $tomorrow_price = $this->asset->histories()
                ->whereType('price')
                ->where('timestamp', 'like', $order->block->block_time->addDay()->toDateString() . '%')
                ->first();

            $tomorrow_tomorrow_price = $this->asset->histories()
                ->whereType('price')
                ->where('timestamp', 'like', $order->block->block_time->addDays(2)->toDateString() . '%')
                ->first();

            $yesterday_price = $this->asset->histories()
                ->whereType('price')
                ->where('timestamp', 'like', $order->block->block_time->subDay()->toDateString() . '%')
                ->first();

            $yesterday_yesterday_price = $this->asset->histories()
                ->whereType('price')
                ->where('timestamp', 'like', $order->block->block_time->subDays(2)->toDateString() . '%')
                ->first();

            if($price)
            {
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $price->value, 100, 8)
                ]);
            }
            elseif($tomorrow_price && $yesterday_price)
            {
                $average_price = ($tomorrow_price->value + $yesterday_price->value) / 2;
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $average_price, 100, 8)
                ]);
            }
            elseif($yesterday_price)
            {
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $yesterday_price->value, 100, 8)
                ]);
            }
            elseif($tomorrow_price)
            {
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $tomorrow_price->value, 100, 8)
                ]);
            }
            elseif($tomorrow_tomorrow_price && $yesterday_yesterday_price)
            {
                $average_price = ($tomorrow_tomorrow_price->value + $yesterday_yesterday_price->value) / 2;
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $average_price, 100, 8)
                ]);
            }
            elseif($yesterday_yesterday_price)
            {
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $yesterday_yesterday_price->value, 100, 8)
                ]);
            }
            elseif($tomorrow_tomorrow_price)
            {
                $order->update([
                    'exchange_rate_usd' => bcdiv($order->exchange_rate * $tomorrow_tomorrow_price->value, 100, 8)
                ]);
            }
        }
    }
}