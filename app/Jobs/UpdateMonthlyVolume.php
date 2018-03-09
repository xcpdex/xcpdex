<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateMonthlyVolume implements ShouldQueue
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
        $block_index = $block->block_index - 4300;

        $quote_volume_usd_month = $this->market->orderMatches()->where('block_index', '>', $block_index)->sum('quote_quantity_usd');

        $this->market->update([
            'quote_volume_usd_month' => $quote_volume_usd_month,
        ]);
    }
}