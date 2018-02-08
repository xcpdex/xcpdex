<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XcpExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export TX';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cols = 'type,tx_hash,fee,size,sats/byte,source,tx_index,block_index,timestamp';
        \Storage::append("export.csv",$cols);

        \App\Transaction::whereProcessed(1)->chunk(10000,function ($transactions) {
            $rows = '';
            foreach ($transactions as $transaction) {
                $rows .= $transaction->type . ',' . $transaction->tx_hash . ',' . $transaction->fee . ',' . $transaction->size . ',' . round($transaction->fee / $transaction->size) . ',' . $transaction->source .',' . $transaction->tx_index . ',' . $transaction->block_index . ',' . $transaction->timestamp . "\n";
            }
            \Storage::append("export.csv",$rows);
        });

    }
}