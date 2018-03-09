<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummariesToCharts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charts', function (Blueprint $table) {
            $table->decimal('midline', 22, 8)->unsigned();
            $table->decimal('volume_usd', 22, 8)->unsigned();
            $table->bigInteger('market_cap')->unsigned();
            $table->decimal('market_cap_usd', 22, 8)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charts', function (Blueprint $table) {
            $table->dropColumn('midline');
            $table->dropColumn('volume_usd');
            $table->dropColumn('market_cap');
            $table->dropColumn('market_cap_usd');
        });
    }
}
