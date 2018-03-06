<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToMarkets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->index('last_price_usd');
            $table->index('quote_volume_usd');
            $table->index('quote_market_cap_usd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropIndex('last_price_usd_index');
            $table->dropIndex('quote_volume_usd_index');
            $table->dropIndex('quote_market_cap_usd_index');
        });
    }
}
