<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class OhlcController extends Controller
{
    public function show($slug)
    {
        ini_set('memory_limit', '-1');

        return \Cache::remember('ohlc_' . $slug, 360, function() use($slug) {
            $market = \App\Market::whereSlug($slug)->firstOrFail();

            $charts = $market->charts()->with('block')
                ->orderBy('block_index', 'asc')
                ->get();

            $last = $market->charts()->with('block')
                ->orderBy('block_index', 'desc')
                ->first();

            $history = [];

            $i = 0;

            if(! $market->orderMatches) return [];

            foreach($charts as $key => $data)
            {
                $history[] = [
                    $data->block->block_time * 1000,
                    (float) $data->open,
                    (float) $data->high,
                    (float) $data->low,
                    (float) $data->close,
                    $data->volume,
                ];

                if(isset($charts[$key + 1]) && $charts[$key + 1]->block_index !== $data->block_index + 1)
                {
                    $timestamp = $data->block->block_time * 1000 + (10 * 60 * 1000);
                    $block_index = $data->block_index + 1;

                    while($block_index < $charts[$key + 1]->block_index)
                    {
                        $history[] = [
                            $timestamp,
                            (float) ((float) $data->high + (float) $data->close) / 2,
                            (float) ((float) $data->high + (float) $data->close) / 2,
                            (float) ((float) $data->high + (float) $data->close) / 2,
                            (float) ((float) $data->high + (float) $data->close) / 2,
                            0,
                        ];
                        $timestamp = $timestamp + (10 * 60 * 1000);
                        $block_index = $block_index + 1;
                    }
                }
            }

            if($last && $last->block_index < \Cache::get('block_height'))
            {
                $timestamp = $last->block->block_time * 1000 + (10 * 60 * 1000);
                $block_index = $last->block_index + 1;

                while($block_index <= \Cache::get('block_height') )
                {
                    $history[] = [
                        $timestamp,
                        (float) ((float) $last->high + (float) $last->close) / 2,
                        (float) ((float) $last->high + (float) $last->close) / 2,
                        (float) ((float) $last->high + (float) $last->close) / 2,
                        (float) ((float) $last->high + (float) $last->close) / 2,
                        0,
                    ];
                    $timestamp = $timestamp + (10 * 60 * 1000);
                    $block_index = $block_index + 1;
                    if($timestamp > \Carbon\Carbon::now()->timestamp * 1000) break;
                }
            }

            return $history;
        });
    }
}