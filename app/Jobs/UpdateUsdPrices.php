<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUsdPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = \App\Order::whereNull('exchange_rate_usd')->get();

        foreach($orders as $order)
        {
            $usd_volume_market = $order->market->quoteAsset->baseMarkets()
                ->orderBy('quote_volume_usd', 'desc')
                ->first();

            if(! $usd_volume_market) continue;

            $exact_match_data = $usd_volume_market->orderMatches()
                ->whereBlockIndex($order->block_index)
                ->first();

            $fuzzy_match_data = $usd_volume_market->orderMatches()
                ->where('block_index', '<', $order->block_index + 72)
                ->where('block_index', '>', $order->block_index - 72)
                ->avg('exchange_rate_usd');

            $extra_fuzzy_match_data = $usd_volume_market->orderMatches()
                ->where('block_index', '<', $order->block_index + 144)
                ->where('block_index', '>', $order->block_index - 144)
                ->avg('exchange_rate_usd');

            $extra_extra_fuzzy_match_data = $usd_volume_market->orderMatches()
                ->where('block_index', '<', $order->block_index + 288)
                ->where('block_index', '>', $order->block_index - 288)
                ->avg('exchange_rate_usd');

            if($exact_match_data)
            {
                $exchange_rate_usd = sprintf("%.8f", (float)$order->exchange_rate * $exact_match_data->exchange_rate_usd);
            }
            elseif($fuzzy_match_data)
            {
                $exchange_rate_usd = sprintf("%.8f", (float)$order->exchange_rate * $fuzzy_match_data);
            }
            elseif($extra_fuzzy_match_data)
            {
                $exchange_rate_usd = sprintf("%.8f", (float)$order->exchange_rate * $extra_fuzzy_match_data);
            }
            elseif($extra_extra_fuzzy_match_data)
            {
                $exchange_rate_usd = sprintf("%.8f", (float)$order->exchange_rate * $extra_extra_fuzzy_match_data);
            }
            else
            {
                $exchange_rate_usd = $order->exchange_rate_usd;
            }

            $order->update(['exchange_rate_usd' => $exchange_rate_usd]);

            if($exchange_rate_usd && $order->orderMatches()->exists())
            {
                foreach($order->orderMatches as $order_match)
                {
                    $quote_quantity = $order_match->quote_quantity;

                    if($order->market->quoteAsset->divisible) $quote_quantity = fromSatoshi($quote_quantity);

                    $quote_quantity_usd = sprintf("%.8f", (float)$quote_quantity * $exchange_rate_usd);

                    $order_match->update([
                        'quote_quantity_usd' => $quote_quantity_usd,
                        'exchange_rate_usd' => $exchange_rate_usd,
                    ]);
                }
            }
        }
    }
}