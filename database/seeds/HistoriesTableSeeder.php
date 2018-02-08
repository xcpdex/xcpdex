<?php

use Illuminate\Database\Seeder;

use App\History;

class HistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $xcp = json_decode(file_get_contents('http://coincap.io/history/XCP', true));

        foreach($xcp->market_cap as $result)
        {
            History::create([
                'type' => 'market_cap',
                'ticker' => 'XCP',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($xcp->price as $result)
        {
            History::create([
                'type' => 'price',
                'ticker' => 'XCP',
                'value' => $result[1] * 100,
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($xcp->volume as $result)
        {
            History::create([
                'type' => 'volume',
                'ticker' => 'XCP',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        $btc = json_decode(file_get_contents('http://coincap.io/history/BTC', true));

        foreach($btc->market_cap as $result)
        {
            History::create([
                'type' => 'market_cap',
                'ticker' => 'BTC',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($btc->price as $result)
        {
            History::create([
                'type' => 'price',
                'ticker' => 'BTC',
                'value' => $result[1] * 100,
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($btc->volume as $result)
        {
            History::create([
                'type' => 'volume',
                'ticker' => 'BTC',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        $ppc = json_decode(file_get_contents('http://coincap.io/history/PEPECASH', true));

        foreach($ppc->market_cap as $result)
        {
            History::create([
                'type' => 'market_cap',
                'ticker' => 'PEPECASH',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($ppc->price as $result)
        {
            History::create([
                'type' => 'price',
                'ticker' => 'PEPECASH',
                'value' => $result[1] * 100,
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }

        foreach($ppc->volume as $result)
        {
            History::create([
                'type' => 'volume',
                'ticker' => 'PEPECASH',
                'value' => $result[1],
                'timestamp' => \Carbon\Carbon::createFromTimestamp($result[0] / 1000),
            ]);
        }
    }
}