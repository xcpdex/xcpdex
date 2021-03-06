<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('market_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->integer('order_match_id')->unsigned()->index();
            $table->integer('block_index')->unsigned()->index();
            $table->integer('tx_index')->unsigned()->index();
            $table->string('status');
            $table->bigInteger('base_quantity')->unsigned();
            $table->bigInteger('quote_quantity')->unsigned();
            $table->decimal('quote_quantity_usd', 22, 8)->unsigned()->nullable();
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
        Schema::dropIfExists('order_matches');
    }
}
