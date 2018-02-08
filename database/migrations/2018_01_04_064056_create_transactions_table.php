<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('offset')->unsigned();
            $table->string('tx_hash')->unique();
            $table->integer('tx_index')->unsigned()->unique();
            $table->integer('block_index')->unsigned();
            $table->bigInteger('total')->unsigned()->default(0);
            $table->integer('fee')->unsigned()->default(0);
            $table->integer('size')->unsigned()->default(0);
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->text('data')->nullable();
            $table->text('tx_hex')->nullable();
            $table->boolean('processed')->default(0);
            $table->boolean('malformed')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
