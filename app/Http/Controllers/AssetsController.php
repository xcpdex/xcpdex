<?php

namespace App\Http\Controllers;

use JsonRPC\Client;

use Illuminate\Http\Request;

class AssetsController extends Controller
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
    public function show(Request $request, $asset)
    {
        $asset = strtoupper($asset);

        $get_assets_info = \Cache::remember("{$asset}.info", 1440, function () use ($asset) {
            return $this->counterblock->execute('get_assets_info', [
                'assetsList' => [$asset]
            ]);
        });

        if ($get_assets_info)
        {
            $get_asset_extended_info = \Cache::remember("{$asset}.extended_info", 1440, function () use ($asset) {
                return $this->counterblock->execute('get_asset_extended_info', [
                    'asset' => $asset
                ]);
            });

            $get_holder_count = \Cache::remember("{$asset}.holder_count", 60, function () use ($asset) {
                return $this->counterparty->execute('get_holder_count', [
                    'asset' => $asset
                ]);
            });

            $get_holders = $get_traders = \Cache::remember("{$asset}.holders", 60, function () use ($asset) {
                return $this->counterparty->execute('get_holders', [
                    'asset' => $asset
                ]);
            });

            usort($get_holders, function($a, $b)
            {
                return $b['address_quantity'] - $a['address_quantity'];
            });

            usort($get_traders, function($a, $b)
            {
                return $b['escrow'] - $a['escrow'];
            });

            $get_market_info = \Cache::remember("{$asset}.market_info", 60, function () use ($asset) {
                return $this->counterblock->execute('get_market_info', [
                    'assets' => [$asset]
                ]);
            });

            $get_asset_history = \Cache::remember("{$asset}.history", 60, function () use ($asset) {
                return $this->counterblock->execute('get_asset_history', [
                    'asset' => $asset,
                    'reverse' => true,
                ]);
            });

            return [
                'info' => $get_assets_info[0],
                'json' => $get_asset_extended_info,
                'holders_count' => $get_holder_count{$asset},
                'holders' => array_slice($get_holders, 0, 10),
                'traders' => array_slice($get_traders, 0, 10),
                'market' => $get_market_info,
                'history' => $get_asset_history,
            ];
        }
    }
}