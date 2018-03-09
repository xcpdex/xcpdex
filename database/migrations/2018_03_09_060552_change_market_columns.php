<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMarketColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->decimal('quote_volume_usd', 26, 8)->change();
            $table->decimal('quote_market_cap_usd', 26, 8)->change();
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
            $table->decimal('quote_volume_usd', 22, 8)->change();
            $table->decimal('quote_market_cap_usd', 22, 8)->change();
        });
    }
}
