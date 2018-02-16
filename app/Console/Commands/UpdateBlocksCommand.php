<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBlocksCommand extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:blocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Establish Block Times';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $blocks = \App\Order::select('block_index')->groupBy('block_index')->get();

        foreach($blocks as $block)
        {
            \App\Jobs\UpdateBlock::dispatch($block->block_index);
        }
    }
}