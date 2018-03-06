<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateChart implements ShouldQueue
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
        $market_id = $this->market->id;

        $block_index = $this->market->charts()->orderBy('block_index', 'desc')->first() ? $this->market->charts()->orderBy('block_index', 'desc')->first()->block_index : 0;

        $blocks = \App\Block::where('block_index', '>=', $block_index)
            ->whereHas('orderMatches', function ($query) use($market_id) {
                $query->where('market_id', '=', $market_id);
            })->get();

        foreach($blocks as $block)
        {
            $matches = $this->market->orderMatches()
                ->whereBlockIndex($block->block_index)
                ->with('order');

            $open = $matches->orderBy('tx_index', 'asc')->first();
            $close = $matches->orderBy('tx_index', 'desc')->first();

            $volume = $matches->sum('quote_quantity');

            if($this->market->quoteAsset->divisible)
            {
                $volume = fromSatoshi($volume);
            }

            $matches = $matches->get();

            $high = $matches->sortByDesc(function($match) {
                return $match->order->exchange_rate;
            })->first();

            $low = $matches->sortBy(function($match) {
                return $match->order->exchange_rate;
            })->first();

            $price = $this->market->quoteAsset->histories()->whereType('price')
                ->where('reported_at', 'like', $block->mined_at->toDateString() . '%')
                ->first();

            if(! $price) return null;

            if(count($matches))
            {
                $price = bcdiv((($high->order->exchange_rate + $low->order->exchange_rate)/2) * $price->value, 100, 8);

                \App\Chart::updateOrCreate([
                    'market_id' => $this->market->id,
                    'block_index' => $block->block_index,
                ],[
                    'open' => $open->order->exchange_rate,
                    'high' => $high->order->exchange_rate,
                    'low' => $low->order->exchange_rate,
                    'close' => $close->order->exchange_rate,
                    'volume' => $volume,
                    'price_usd' => $price,
                ]);
            }
        }
    }
}