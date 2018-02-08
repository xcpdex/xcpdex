<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(HistoriesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
    }
}
