<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('flight_from');
            $table->bigInteger('flight_back');
            $table->date('date_from');
            $table->date('date_back');
            $table->string('code', 5);
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
        Schema::dropIfExists('bookings_models');
    }
}
