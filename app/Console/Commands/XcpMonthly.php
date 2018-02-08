<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XcpMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export CSV';

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
        $types = ['broadcasts', 'bets', 'btcpays', 'burns', 'cancels', 'dividends', 'issuances', 'orders', 'rps', 'sends'];
        foreach($types as $type) {
            $data = \App\Transaction::whereType($type)->select(\DB::raw('count(id) as txs'), \DB::raw('sum(cents) as tcents'), \DB::raw('sum(fee) as tfees'), \DB::raw('sum(size) as tsize'), \DB::raw('avg(cents) as acents'), \DB::raw('avg(fee) as afees'), \DB::raw('avg(size) as asize'), \DB::raw('max(cents) as hcents'), \DB::raw('max(fee) as hfees'), \DB::raw('max(size) as hsize'), \DB::raw('min(cents) as lcents'), \DB::raw('min(fee) as lfees'), \DB::raw('min(size) as lsize'), \DB::raw("DATE_FORMAT(timestamp,'%M %Y') as months"))->groupBy('months')->get();
            \Storage::append("/monthly/{$type}.csv", ", , Total, , , Average, , , Maximum, , , Minimum, , ");
            \Storage::append("/monthly/{$type}.csv", "Month, Transactions, Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes)");
            foreach($data as $d) {
                \Storage::append("/monthly/{$type}.csv", "{$d->months}, {$d->txs}, {$d->tcents}, {$d->tfees}, {$d->tsize}, {$d->acents}, {$d->afees}, {$d->asize}, {$d->hcents}, {$d->hfees}, {$d->hsize}, {$d->lcents}, {$d->lfees}, {$d->lsize}");
            }
        }

        $data = \App\Transaction::select(\DB::raw('count(id) as txs'), \DB::raw('sum(cents) as tcents'), \DB::raw('sum(fee) as tfees'), \DB::raw('sum(size) as tsize'), \DB::raw('avg(cents) as acents'), \DB::raw('avg(fee) as afees'), \DB::raw('avg(size) as asize'), \DB::raw('max(cents) as hcents'), \DB::raw('max(fee) as hfees'), \DB::raw('max(size) as hsize'), \DB::raw('min(cents) as lcents'), \DB::raw('min(fee) as lfees'), \DB::raw('min(size) as lsize'), \DB::raw("DATE_FORMAT(timestamp,'%M %Y') as months"))->groupBy('months')->get();
        \Storage::append("/monthly/transactions.csv", ", , Total, , , Average, , , Maximum, , , Minimum, , ");
        \Storage::append("/monthly/transactions.csv", "Month, Transactions, Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes), Fees (cents), Fees (satoshis), Size (bytes)");
        foreach($data as $d) {
            \Storage::append("/monthly/transactions.csv", "{$d->months}, {$d->txs}, {$d->tcents}, {$d->tfees}, {$d->tsize}, {$d->acents}, {$d->afees}, {$d->asize}, {$d->hcents}, {$d->hfees}, {$d->hsize}, {$d->lcents}, {$d->lfees}, {$d->lsize}");
        }
    }
}