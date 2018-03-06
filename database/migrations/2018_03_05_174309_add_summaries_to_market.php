<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummariesToMarket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->integer('open_orders_total')->unsigned()->index()->default(0);
            $table->integer('orders_total')->unsigned()->index()->default(0);
            $table->integer('order_matches_total')->unsigned()->index()->default(0);
            $table->timestamp('last_traded_at')->nullable();
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
            $table->dropColumn('open_orders_total');
            $table->dropColumn('orders_total');
            $table->dropColumn('order_matches_total');
            $table->dropColumn('last_traded_at');
        });
    }
}
