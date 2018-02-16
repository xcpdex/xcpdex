<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HighChartsController extends Controller
{
    protected $counterblock;

    public function __construct()
    {
        $this->counterblock = new \JsonRPC\Client(env('CB_API'));
        $this->counterblock->authentication(env('CB_USER'), env('CB_PASS'));
    }

    public function show($slug)
    {
        $market = \App\Market::whereSlug($slug)->firstOrFail();

        $data = $this->counterblock->execute('get_market_price_history', [
            'asset1'   => $market->baseAsset->name,
            'asset2'   => $market->quoteAsset->name,
            'start_ts' => 1387065600,
            'as_dict'  => True
        ]);

        $history = [];

        $i = 0;

        if(! $data) return [];

        foreach($data as $row)
        {
            $history[] = [
                $row['interval_time'],
                $row['midline'],
                round($row['vol'] * $row['midline']),
            ];
        }

        return $history;
    }
}