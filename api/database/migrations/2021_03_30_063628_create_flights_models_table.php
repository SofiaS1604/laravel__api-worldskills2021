<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('flight_code');
            $table->integer('from_id');
            $table->integer('to_id');
            $table->time('time_from');
            $table->time('time_to');
            $table->integer('cost');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights_models');
    }
}
