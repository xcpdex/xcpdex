<?php

use Illuminate\Database\Seeder;

use App\Transaction;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(storage_path('app/transactions.csv'), 'r');

        while (($data = fgetcsv($file, 0, ",")) !== FALSE)
        {
            $data = array_map('trim', $data);

            if(isset($data[2]))
            {
                Transaction::firstOrCreate([
                    'tx_hash' => $data[2],
                ],[
                    'type' => $data[0],
                    'offset' => $data[1] - 1000,
                    'tx_index' => $data[3],
                    'block_index' => $data[4],
                ]);
            }
            else
            {
                \Storage::append("not-set.csv", serialize($data));
            }
        }
    }
}