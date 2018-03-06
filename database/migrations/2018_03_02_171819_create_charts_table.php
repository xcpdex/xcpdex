<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('market_id')->unsigned()->index();
            $table->integer('block_index')->unsigned()->index();
            $table->decimal('open', 22, 8)->unsigned();
            $table->decimal('high', 22, 8)->unsigned();
            $table->decimal('low', 22, 8)->unsigned();
            $table->decimal('close', 22, 8)->unsigned();
            $table->bigInteger('volume')->unsigned();
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
        Schema::dropIfExists('charts');
    }
}
