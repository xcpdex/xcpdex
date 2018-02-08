<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XcpCents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:cents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TX USD';

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
        // $histories = \App\History::whereTicker('BTC')->whereType('price')->where('timestamp', 'like', '2018%')->get();

        // foreach($histories as $history)
        // {
        //     $txs = \App\Transaction::whereCents(0)->where('timestamp', 'like', substr($history->timestamp, 0, 10) . '%')->get();

        //    foreach($txs as $tx)
        //    {
        //        $tx->update(['cents' => round(($history->value / 100000000) * $tx->fee, 0), 'malformed' => 0]);
        //    }
        // }

        $txs = \App\Transaction::whereCents(0)->get();

        foreach($txs as $tx)
        {
            $history = \App\History::whereTicker('BTC')->whereType('price')->where('timestamp', 'like', substr($tx->timestamp, 0, 10) . '%')->first();
            if($history) $tx->update(['cents' => round(($history->value / 100000000) * $tx->fee, 0), 'malformed' => 0]);
        }
    }
}