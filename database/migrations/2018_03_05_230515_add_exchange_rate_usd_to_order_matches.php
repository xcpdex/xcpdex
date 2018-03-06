<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExchangeRateUsdToOrderMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_matches', function (Blueprint $table) {
            $table->decimal('exchange_rate_usd', 22, 8)->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_matches', function (Blueprint $table) {
            $table->dropColumn('exchange_rate_usd');
        });
    }
}
