<?php

namespace App\Console\Commands;

use JsonRPC\Client;
use JsonRPC\Exception\InvalidJsonFormatException;

use Illuminate\Console\Command;

class TradeHistory extends Command
{
    protected $counterblock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:history {asset1} {asset2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Trade History';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterblock = new Client(env('CB_API', 'http://wallet.counterwallet.io:4100/api/'));
        $this->counterblock->authentication(env('CB_USER', 'rpc'), env('CB_PASS', 'rpc'));

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $asset1 = $this->argument('asset1');
        $asset2 = $this->argument('asset2');

        $col = 'usd_equiv,unit_price,unit_price_inverse,quote_asset,message_index,base_quantity_normalized,order_match_tx0_index,base_quantity,order_match_tx0_address,block_time,order_match_tx1_address,order_match_tx1_index,base_asset,quote_quantity_normalized,quote_quantity,order_match_id,block_index';
        \Storage::append("{$asset1}-{$asset2}.csv", $col);

        $end_ts = \Carbon\Carbon::now()->timestamp;
        $last_m = 0;

        while($end_ts >= 1385856000)
        {
            $history = $this->counterblock->execute('get_trade_history', [
                'asset1' => $asset1,
                'asset2' => $asset2,
                'start_ts' => 1385856000,
                'end_ts' => $end_ts,
                'limit' => 50,
            ]);

            foreach($history as $h)
            {
                if($last_m === $h['message_index']) continue;

                $quote = \App\History::whereTicker('XCP')->whereType('price')->where('timestamp', 'like', \Carbon\Carbon::createFromTimestamp($h['block_time'] / 1000)->toDateString() . '%')->first();
                $usd_e = $quote ? $quote->value * $h['quote_quantity_normalized'] : 'NA';
                $row = "{$usd_e},{$h['unit_price']},{$h['unit_price_inverse']},{$h['quote_asset']},{$h['message_index']},{$h['base_quantity_normalized']},{$h['order_match_tx0_index']},{$h['base_quantity']},{$h['order_match_tx0_address']},{$h['block_time']},{$h['order_match_tx1_address']},{$h['order_match_tx1_index']},{$h['base_asset']},{$h['quote_quantity_normalized']},{$h['quote_quantity']},{$h['order_match_id']},{$h['block_index']}";
                \Storage::append("{$asset1}-{$asset2}.csv", $row);
            }

            $end_ts = $h['block_time'] / 1000;
            $last_m = $h['message_index'];
        }
    }
}