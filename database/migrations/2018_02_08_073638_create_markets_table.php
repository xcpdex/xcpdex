<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('base_asset_id')->unsigned()->index();
            $table->integer('quote_asset_id')->unsigned()->index();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->bigInteger('base_volume')->default(0);
            $table->decimal('last_price_usd', 22, 8)->unsigned()->default(0);
            $table->bigInteger('quote_volume')->default(0);
            $table->decimal('quote_volume_usd', 22, 8)->unsigned()->default(0);
            $table->bigInteger('quote_market_cap')->default(0);
            $table->decimal('quote_market_cap_usd', 22, 8)->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markets');
    }
}
