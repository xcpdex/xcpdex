<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MempoolController extends Controller
{
    protected $counterparty;

    public function __construct()
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $txs = \Cache::remember('mempool_index', 10, function() {
            return $this->counterparty->execute('get_mempool');
        });

        return view('mempool.index', compact('txs'));
    }
}
