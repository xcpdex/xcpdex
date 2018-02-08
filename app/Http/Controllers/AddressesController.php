<?php

namespace App\Http\Controllers;

use JsonRPC\Client;
use JsonRPC\Exception\ResponseException;

use Illuminate\Http\Request;

class AddressesController extends Controller
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
    public function show(Request $request, $address)
    {
        $get_pubkey_for_address = \Cache::remember("{$address}.pubkey", 1440, function () use ($address) {
            try{
                return $this->counterblock->execute('get_pubkey_for_address', [
                    'address' => $address
                ]);
            }catch(ResponseException $e){
                return false;
            }
        });

        if($get_pubkey_for_address)
        {
            $get_chain_address_info = \Cache::remember("{$address}.info", 60, function () use ($address) {
                return $this->counterblock->execute('get_chain_address_info', [
                    'addresses' => [$address]
                ]);
            });

            $get_owned_assets = \Cache::remember("{$address}.assets", 60, function () use ($address) {
                return $this->counterblock->execute('get_owned_assets', [
                    'addresses' => [$address]
                ]);
            });


            $get_normalized_balances = \Cache::remember("{$address}.balances", 60, function () use ($address) {
                return $this->counterblock->execute('get_normalized_balances', [
                    'addresses' => [$address]
                ]);
            });


            $get_escrowed_balances = \Cache::remember("{$address}.escrowed", 60, function () use ($address) {
                return $this->counterblock->execute('get_escrowed_balances', [
                    'addresses' => [$address]
                ]);
            });

            return [
                'info' => $get_chain_address_info[0],
                'assets' => $get_owned_assets ? $get_owned_assets[0] : $get_owned_assets,
                'balances' => $get_normalized_balances,
                'escrowed' => $get_escrowed_balances,
            ];
        }
    }
}