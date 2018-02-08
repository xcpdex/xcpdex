<?php

namespace App\Console\Commands;

use JsonRPC\Client;
use JsonRPC\Exception\InvalidJsonFormatException;

use Illuminate\Console\Command;

class XcpTime extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TX Time';

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
        \App\Transaction::whereNull('timestamp')->orderBy('id', 'desc')->chunk(100, function ($transactions) {
            foreach ($transactions as $transaction) {
                try {
                    $raw = $this->counterparty->execute('getrawtransaction', [
                        'tx_hash' => $transaction->tx_hash,
                        'verbose' => true,
                    ]);
                } catch(InvalidJsonFormatException $e) {
                    $transaction->update([
                        'malformed' => 1,
                    ]);
                }

                try {
                    $transaction->update([
                        'timestamp' => \Carbon\Carbon::createFromTimestamp($raw['time'])->addHour(),
                        'malformed' => 0,
                    ]);
                } catch(\Illuminate\Database\QueryException $e) {
                    $transaction->update([
                        'malformed' => 1,
                    ]);
                } catch(\PDOException $e) {
                    $transaction->update([
                        'malformed' => 1,
                    ]);
                }
            }
        });

    }
}