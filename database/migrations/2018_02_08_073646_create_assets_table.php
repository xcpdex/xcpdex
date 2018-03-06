<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('long_name')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('issuance')->unsigned()->default(0);
            $table->boolean('divisible')->default(0);
            $table->boolean('locked')->default(0);
            $table->boolean('enhanced')->default(0);
            $table->boolean('processed')->default(0);
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
