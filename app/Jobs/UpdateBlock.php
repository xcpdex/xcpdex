<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBlock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $block_index;
    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($block_index)
    {
        $this->block_index = $block_index;
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
        $block_info = $this->counterparty->execute('get_block_info', ['block_index' => $this->block_index]);

        \App\Block::firstOrCreate([
            'block_index' => $block_info['block_index'],
            'block_hash' => $block_info['block_hash'],
        ],[
            'block_time' => \Carbon\Carbon::createFromTimestamp($block_info['block_time'], 'America/New_York'),
        ]);
    }
}
