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
        $block = \App\Block::orderBy('block_index', 'desc')->first();

        $base_asset_volume_total_usd = $this->market->baseAsset->baseMarketsOrderMatches()->sum('quote_quantity_usd') + $this->market->baseAsset->quoteMarketsOrderMatches()->sum('quote_quantity_usd');
        $base_asset_orders_total = $this->market->baseAsset->baseMarketsOrders()->count() + $this->market->baseAsset->quoteMarketsOrders()->count();
        $base_asset_order_matches_total = $this->market->baseAsset->baseMarketsOrderMatches->count() + $this->market->baseAsset->quoteMarketsOrderMatches->count();

        $this->market->baseAsset->update([
            'volume_total_usd' => $base_asset_volume_total_usd,
            'orders_total' => $base_asset_orders_total,
            'order_matches_total' => $base_asset_order_matches_total,
        ]);

        $quote_asset_volume_total_usd = $this->market->quoteAsset->baseMarketsOrderMatches()->sum('quote_quantity_usd') + $this->market->quoteAsset->quoteMarketsOrderMatches()->sum('quote_quantity_usd');
        $quote_asset_orders_total = $this->market->quoteAsset->baseMarketsOrders()->count() + $this->market->quoteAsset->quoteMarketsOrders()->count();
        $quote_asset_order_matches_total = $this->market->quoteAsset->baseMarketsOrderMatches->count() + $this->market->quoteAsset->quoteMarketsOrderMatches->count();

        $this->market->quoteAsset->update([
            'volume_total_usd' => $quote_asset_volume_total_usd,
            'orders_total' => $quote_asset_orders_total,
            'order_matches_total' => $quote_asset_order_matches_total,
        ]);

        $base_volume = $this->market->orderMatches()->sum('base_quantity');
        $last_price_usd = $this->market->lastMatch && $this->market->lastMatch->order->exchange_rate_usd ? $this->market->lastMatch->order->exchange_rate_usd : 0;
        $quote_volume = $this->market->orderMatches()->sum('quote_quantity');
        $quote_volume_usd = $this->market->orderMatches()->sum('quote_quantity_usd');
        $quote_volume_usd_month = $this->market->orderMatches()->where('block_index', '>',$block->block_index - 4300)->sum('quote_quantity_usd');
        $quote_market_cap = $this->market->lastMatch ? isset($this->market->baseAsset->meta['burned']) ? (($this->market->baseAsset->issuance_normalized - $this->market->baseAsset->meta['burned'])) * $this->market->lastMatch->order->exchange_rate : $this->market->baseAsset->issuance_normalized * $this->market->lastMatch->order->exchange_rate : 0;
        $quote_market_cap_usd = $this->market->lastMatch ? isset($this->market->baseAsset->meta['burned']) ? (($this->market->baseAsset->issuance_normalized - $this->market->baseAsset->meta['burned'])) * $this->market->lastMatch->order->exchange_rate_usd : $this->market->baseAsset->issuance_normalized * $this->market->lastMatch->order->exchange_rate_usd : 0;
        $open_orders_total = $this->market->openOrders->count();
        $orders_total = $this->market->orders->count();
        $order_matches_total = $this->market->orderMatches->count();
        $last_traded_at = $this->market->lastMatch ? $this->market->lastMatch->block->mined_at : null;

        if($quote_volume_usd > 99999999999999999) $quote_volume_usd = 99999999999999999;
        if($quote_market_cap_usd > 99999999999999999) $quote_market_cap_usd = 99999999999999999;

        $this->market->update([
            'base_volume' => $base_volume,
            'last_price_usd' => $last_price_usd,
            'quote_volume' => $quote_volume,
            'quote_volume_usd' => $quote_volume_usd,
            'quote_volume_usd_month' => $quote_volume_usd_month,
            'quote_market_cap' => $quote_market_cap,
            'quote_market_cap_usd' => $quote_market_cap_usd,
            'open_orders_total' => $open_orders_total,
            'orders_total' => $orders_total,
            'order_matches_total' => $order_matches_total,
            'last_traded_at' => $last_traded_at,
        ]);
    }
}