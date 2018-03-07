<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummariesToAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->decimal('volume_total_usd', 22, 8)->unsigned()->default(0);
            $table->integer('orders_total')->unsigned()->index()->default(0);
            $table->integer('order_matches_total')->unsigned()->index()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('volume_total_usd');
            $table->dropColumn('orders_total');
            $table->dropColumn('order_matches_total');
        });
    }
}
