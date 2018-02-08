<?php

namespace App\Console\Commands;

use JsonRPC\Client;

use Illuminate\Console\Command;

class XcpUpdate extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counterparty XCP Update';

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
        $tables = ['bets', 'broadcasts', 'btcpays', 'burns', 'cancels', 'dividends', 'issuances', 'orders', 'rps', 'sends'];

        foreach($tables as $table)
        {
            $offset = \App\Transaction::whereType($table)->count();

            while($offset <= 1000000)
            {
                $results = $this->counterparty->execute('get_' . $table, [
                    'limit' => 1000,
                    'offset' => $offset,
                ]);

                if(! count($results)) break;

                foreach($results as $result)
                {
                    $result = array_map('trim', $result);

                    if(isset($result['tx_hash']))
                    {
                        \App\Transaction::firstOrCreate([
                            'tx_hash' => $result['tx_hash'],
                        ],[
                            'type' => $table,
                            'offset' => $offset - 1000,
                            'tx_index' => $result['tx_index'],
                            'block_index' => $result['block_index'],
                        ]);
                    }
                }
            }
        }
    }
}
