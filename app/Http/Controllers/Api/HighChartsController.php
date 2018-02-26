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
        return \Cache::remember('chart_' . $slug, 360, function() use($slug) {
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

            foreach($data as $key => $row)
            {
                $history[] = [
                    $row['interval_time'],
                    $row['midline'],
                    round($row['vol'] * $row['midline']),
                ];

                if(isset($data[$key + 1]) && $data[$key + 1]['interval_time'] - $row['interval_time'] > 3600000)
                {
                    $timestamp = $row['interval_time'] + 3600000;

                    while($timestamp <= $data[$key + 1]['interval_time'])
                    {
                        $history[] = [
                            $timestamp,
                            $row['midline'],
                            0,
                        ];
                        $timestamp = $timestamp + 3600000;
                    }
                }
            }

            $last = end($data);

            if(\Carbon\Carbon::now()->timestamp * 1000 - $last['interval_time'] > 3600000)
            {
                $timestamp = $last['interval_time'] + 3600000;

                while($timestamp <= \Carbon\Carbon::now()->timestamp * 1000)
                {
                    $history[] = [
                        $timestamp,
                        $last['midline'],
                        0,
                    ];
                    $timestamp = $timestamp + 3600000;
                }
            }

            return $history;
        });
    }
}