<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        return $this->counterparty->execute('get_blocks', [
            'block_indexes' => [283873],
        ]);

        return $this->counterparty->execute('get_btcpays', [
            'filters' => [
                [
                    'field' => 'order_match_id',
                    'op'    => 'LIKE',
                    'value' => 'dd122ee022bdda56b5338c1602effc04d0f96ef4c7850929b8889d55c74fe09c%',
                ],
            ],
        ]);
    }

    private function find_key_value($array, $key, $val)
    {
        foreach ($array as $item)
        {
            if (is_array($item) && $this->find_key_value($item, $key, $val)) return true;

            if (isset($item[$key]) && $item[$key] == $val) return true;
        }

        return false;
    }
}
