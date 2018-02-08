<?php

namespace App\Http\Controllers;

use JsonRPC\Client;
use JsonRPC\Exception\ResponseException;

use Illuminate\Http\Request;

class TestController extends Controller
{
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
        $this->counterparty = new Client(env('CP_API', 'http://wallet.counterwallet.io:4000/api/'));
        $this->counterparty->authentication(env('CP_USER', 'rpc'), env('CP_PASS', 'rpc'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Open Orders

        $sells = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'give_asset',
                    'op'    => '==',
                    'value' => 'PEPECASH',
                ],[
                    'field' => 'get_asset',
                    'op'    => '==',
                    'value' => 'XCP',
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'open',
                ],
            ],
        ]);

        $buys = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'give_asset',
                    'op'    => '==',
                    'value' => 'XCP',
                ],[
                    'field' => 'get_asset',
                    'op'    => '==',
                    'value' => 'PEPECASH',
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'open',
                ],
            ],
        ]);

        return [
            'sells_count' => count($sells),
            'buys_count' => count($buys),
            'sells' => $sells,
            'buys' => $buys,
        ];

        // Match History

        $sells = $this->counterparty->execute('get_order_matches', [
            'filters' => [
                [
                    'field' => 'forward_asset',
                    'op'    => '==',
                    'value' => 'PEPECASH',
                ],[
                    'field' => 'backward_asset',
                    'op'    => '==',
                    'value' => 'XCP',
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'completed',
                ],
            ],
        ]);

        $buys = $this->counterparty->execute('get_order_matches', [
            'filters' => [
                [
                    'field' => 'forward_asset',
                    'op'    => '==',
                    'value' => 'XCP',
                ],[
                    'field' => 'backward_asset',
                    'op'    => '==',
                    'value' => 'PEPECASH',
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'completed',
                ],
            ],
        ]);

        return [
            'sells_count' => count($sells),
            'buys_count' => count($buys),
            'sells' => $sells,
            'buys' => $buys,
        ];

        return $this->counterparty->execute('get_balances', array('filters' => array('field' => 'asset', 'op' => '==', 'value' => 'PEPECASH')));

        return $this->counterparty->execute('get_holders', [
            'asset' => "PEPECASH",
        ]);

        $a = $this->counterparty->execute('get_issuances', [
            'filters' => [
                'field' => 'asset',
                'op' => '==',
                'value' => 'PEPECASH'
            ],
        ]);

        return json_encode(end($a));

        return $this->counterparty->execute('get_holders', [
            'asset' => "PEPECASH",
        ]);

        return $this->counterblock->execute('get_trade_history', [
            'asset1' => 'PEPECASH',
            'asset2' => 'XCP',
            'start_ts' => 1385856000,
            'end_ts' => 1516159465,
            'limit' => 50,
        ]);

        return $this->counterblock->execute('get_balance_history', [
            'asset' => 'GUERILLA',
            'addresses' => ['1ENKkgvMqjPmC3Wst631jbpaC3ULkYgkgL'],
            'start_ts' => 1385856000,
            'end_ts' => 1516159465,
        ]);

        return $this->counterparty->execute('get_tx_info', [
            'tx_hex' => '01000000017b66db9fdbdddc72fe24bfc9a85842d8bd68f75300756b7b7bbff250e2874071020000006a47304402207207e0204153c828ef3357252a52d522a07f43ba2024582312f02507c4b57ca1022037f2182ee6f664d235879119c6ff10d53e2dfa6bce0126bcb68af78ce2c4dd32012102a51147c9e3a554ed35e20cc5ca0fef20e47ae976cfe06a594e135e416bb05e32ffffffff0300000000000000001e6a1cd5a46d783d4d425b6e0c4fe1bcc1bc3fb8827e67e81199ff98d0d2f636150000000000001976a9149c834113474128c7c59eec778d57cbcca48839be88ac1b1b7d00000000001976a9144de50dc01362d6ad9b4365a56167dae8c029bba188ac00000000',
            'block_index' => 468697,
        ]);

        return $this->counterparty->execute('getrawtransaction', [
            'tx_hash' => "54d181aba863bec612355a724095d75fa49fde9c8e161b6240950de1a6b46958",
            'verbose' => true,
        ]);

        return $this->counterblock->execute('get_market_orders', ['asset1' => 'VACUS', 'asset2' => 'XCP']);

        return $this->counterparty->execute('get_balances', array('filters' => array('field' => 'address', 'op' => '==', 'value' => '1JEgEu7PPPizVhd1bLygp1ehizKe2nhmcH')));

        return $this->counterparty->execute('getrawtransaction', [
            'tx_hash' => '72d99fc54e1486fe328b1ad68dd8d8b50db10db2fbe6c1fee0ba2e6f9a9db5b0',
            'verbose' => true,
        ]);

        return $this->counterparty->execute('get_tx_info', [
            'tx_hex' => '0100000001e041877e4dac66fb895d020740936ed0083c2f4ae99dbf2a96497ace72b8b356020000006a47304402202d5096c45f8031e45222a1550301efff58adabaa651bc63f377a7ce6b83ca1a10220288ec66a8b33bcf0a3cfef38f0894cd98b5d07afab94f4a04e1faf156a532a9e012102bd19f54f4ec9fa9a9d36cc9157aa71b1071305e52833330e2e41e378092ce5ceffffffff0200000000000000002c6a2a1b33b5ab1722a725bc01f2a995d7a4e26cfff353af8b0aa0cb99fa0e0c15af0e1513033eadf774ef471704c03600000000001976a914d3a04acf375c2f12af9391692cf92838c95e044988ac00000000',
            'block_index' => 468697,
        ]);
    }
}