<?php

use Illuminate\Database\Seeder;

class AssetsTableSeeder extends Seeder
{
    protected $counterparty;

    public function __construct()
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assets = ['BTC', 'XCP'];

        foreach($assets as $asset)
        {
            $issuance = $this->counterparty->execute('get_supply', [
                'asset' => $asset,
            ]);

            \App\Asset::firstOrCreate([
                'name' => $asset,
                'issuance' => $issuance,
                'divisible' => 1,
                'processed' => 1,
            ]);
        }
    }
}
