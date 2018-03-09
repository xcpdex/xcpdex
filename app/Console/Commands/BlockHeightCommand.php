<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BlockHeightCommand extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block:height';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor Block Height';

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
        if($this->isNewBlockHeight())
        {
            $markets = \App\Market::whereHas('orders', function($order) {
                $order->where('expire_index', '=', \Cache::get('block_height'));
            })->get();

            foreach($markets as $market)
            {
                $market->update(['open_orders_total' => $market->openOrders->count()]);
            }

            $this->call('fetch:assets', ['method' => 'update']);
            sleep(180);
            $this->call('update:histories');
            sleep(20);
            $this->call('update:blocks');
            // $this->call('update:charts');
        }
    }

    private function isNewBlockHeight()
    {
        $block_height = $this->getBlockHeight();

        if($block_height !== \Cache::get('block_height'))
        {
            $this->setBlockHeight($block_height);

            return true;
        }

        return false;
    }

    private function getBlockHeight()
    {
        $info = $this->counterparty->execute('get_running_info');

        return $info['bitcoin_block_count'];
    }

    private function setBlockHeight($block_height)
    {
        \Cache::forget('block_height');
        \Cache::forever('block_height', $block_height);
    }
}