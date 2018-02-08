<?php

namespace App\Http\Controllers\Api;

use JsonRPC\Client;

use App\Http\Controllers\Controller;

class AssetsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | API Assets Controller
    |--------------------------------------------------------------------------
    |
    | This controller gathers various information about a given asset from the
    | Counterparty and Counterblock APIs and returns it as a JSON object.
    |
    */

    protected $counterblock;

    /**
     * Establish JSON-RPC connections.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * Counterblock API
         */
        $this->counterblock = new Client(env('CB_API', 'http://wallet.counterwallet.io:4100/api/'));
        $this->counterblock->authentication(env('CB_USER', 'rpc'), env('CB_PASS', 'rpc'));
    }

    /**
     * Gathers and returns the data.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($asset)
    {
        $asset = strtoupper($asset);

        /**
         * CB - get_assets_info
         * Returns information on the specified asset.
         * https://counterparty.io/docs/counterblock_api/#get_assets_info
         */
        $get_assets_info = \Cache::remember("{$asset}.info", 1440, function () use ($asset) {
            return $this->counterblock->execute('get_assets_info', [
                'assetsList' => [$asset]
            ]);
        });

        if ($get_assets_info) {
            return $get_assets_info[0];
        } else {
            return [ 'status' => 404 ];
        }
    }
}
