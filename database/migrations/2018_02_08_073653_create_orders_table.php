<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('market_id')->unsigned()->index();
            $table->integer('block_index')->unsigned()->index();
            $table->integer('expire_index')->unsigned()->index();
            $table->integer('tx_index')->unsigned()->index();
            $table->string('tx_hash')->unique();
            $table->string('source');
            $table->string('status');
            $table->string('type');
            $table->bigInteger('base_quantity')->unsigned();
            $table->bigInteger('base_remaining');
            $table->bigInteger('quote_quantity')->unsigned();
            $table->bigInteger('quote_remaining');
            $table->decimal('exchange_rate', 22, 8)->unsigned();
            $table->decimal('exchange_rate_usd', 22, 8)->unsigned()->nullable();
            $table->bigInteger('fee_paid')->unsigned();
            $table->integer('duration')->unsigned();
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
        Schema::dropIfExists('orders');
    }
}
