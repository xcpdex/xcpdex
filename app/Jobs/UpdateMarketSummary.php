<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateMarketSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $market;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Market $market)
    {
        $this->market = $market;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $base_volume = $this->market->orderMatches()->sum('base_quantity');
        $last_price_usd = $this->market->lastMatch && $this->market->lastMatch->order->exchange_rate_usd ? $this->market->lastMatch->order->exchange_rate_usd : 0;
        $quote_volume = $this->market->orderMatches()->sum('quote_quantity');
        $quote_volume_usd = $this->market->orderMatches()->sum('quote_quantity_usd');
        $quote_market_cap = $this->market->lastMatch ? isset($this->market->baseAsset->meta['burned']) ? (($this->market->baseAsset->issuance_normalized - $this->market->baseAsset->meta['burned'])) * $this->market->lastMatch->order->exchange_rate : $this->market->baseAsset->issuance_normalized * $this->market->lastMatch->order->exchange_rate : 0;
        $quote_market_cap_usd = $this->market->lastMatch ? isset($this->market->baseAsset->meta['burned']) ? (($this->market->baseAsset->issuance_normalized - $this->market->baseAsset->meta['burned'])) * $this->market->lastMatch->order->exchange_rate_usd : $this->market->baseAsset->issuance_normalized * $this->market->lastMatch->order->exchange_rate_usd : 0;
        $open_orders_total = $this->market->openOrders->count();
        $orders_total = $this->market->orders->count();
        $order_matches_total = $this->market->orderMatches->count();
        $last_traded_at = $this->market->lastMatch ? $this->market->lastMatch->block->mined_at : null;

        $this->market->update([
            'base_volume' => $base_volume,
            'last_price_usd' => $last_price_usd,
            'quote_volume' => $quote_volume,
            'quote_volume_usd' => $quote_volume_usd,
            'quote_market_cap' => $quote_market_cap,
            'quote_market_cap_usd' => $quote_market_cap_usd,
            'open_orders_total' => $open_orders_total,
            'orders_total' => $orders_total,
            'order_matches_total' => $order_matches_total,
            'last_traded_at' => $last_traded_at,
        ]);
    }
}