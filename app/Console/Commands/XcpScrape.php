<?php

namespace App\Console\Commands;

use JsonRPC\Client;

use Illuminate\Console\Command;

class XcpScrape extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcp:scrape {table} {offset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counterparty XCP Scrape';

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
        $table = $this->argument('table');
        $offset = (int) $this->argument('offset');

        while($offset <= 850000)
        {
            $results = $this->counterparty->execute('get_' . $table, [
                'limit' => 1000,
                'offset' => $offset,
            ]);

            $file = floor($offset / 1000);

            $offset = $offset + 1000;

            if(! count($results)) break;

            foreach($results as $result) {
                $row = "{$table}, {$offset}, {$result['tx_hash']}, {$result['tx_index']}, {$result['block_index']}";
                \Storage::append("{$table}.{$file}.csv", $row);
            }
        }
    }
}
