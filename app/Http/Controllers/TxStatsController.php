<?php

namespace App\Http\Controllers;

use JsonRPC\Client;
use JsonRPC\Exception\ResponseException;

use Illuminate\Http\Request;

class TxStatsController extends Controller
{
    protected $counterblock;
    protected $counterparty;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * API Connections
         */
        $this->counterblock = new Client(env('CB_API', 'http://public.coindaddy.io:4100/api/'));
        $this->counterblock->authentication(env('CB_USER', 'rpc'), env('CB_PASS', '1234'));
        $this->counterparty = new Client(env('CP_API', 'http://public.coindaddy.io:4000/api/'));
        $this->counterparty->authentication(env('CP_USER', 'rpc'), env('CP_PASS', '1234'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $tx_stats = \Cache::remember("tx_stats", 1440, function () {
            return $this->counterblock->execute('get_transaction_stats', ['start_ts' => 1385856000]);
        });

        // return $tx_stats;

        return view('stats', compact('tx_stats'));

    }
}