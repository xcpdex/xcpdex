<?php

namespace App\Console\Commands;

use JsonRPC\Client;
use JsonRPC\Exception\InvalidJsonFormatException;

use Illuminate\Console\Command;

class XcpFees extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TX Hex';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new Client(env('CP_API', 'http://wallet.counterwallet.io:4000/api/'));
        $this->counterparty->authentication(env('CP_USER', 'rpc'), env('CP_PASS', 'rpc'));

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\Transaction::whereNotNull('tx_hex')->whereNull('source')->chunk(100, function ($transactions) {
            foreach ($transactions as $transaction) {
                try {
                    $info = $this->counterparty->execute('get_tx_info', [
                        'tx_hex' => $transaction->tx_hex,
                        'block_index' => $transaction->block_index,
                    ]);
                } catch(InvalidJsonFormatException $e) {
                    $transaction->update([
                        'malformed' => 1,
                    ]);
                }
                $transaction->update([
                    'source' => $info[0],
                    'destination' => $info[1],
                    'total' => is_null($info[2]) ? 0 : $info[2],
                    'fee' => $info[3],
                    'data' => $info[4],
                    'processed' => 1,
                    'malformed' => 0,
                ]);
            }
        });

    }
}