<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charging_stations', function (Blueprint $table) {
            $table->id();
            $table->string("charging_station_name");
            $table->unsignedBigInteger('location');

            $table->unsignedInteger('ac_ports_fast');
            $table->unsignedInteger('dc_ports_fast');
            $table->unsignedInteger('ac_ports_regular');
            $table->unsignedInteger('dc_ports_regular');
            $table->unsignedDouble('nearest_restaurant');
            $table->unsignedDouble('nearest_shopping_mall');
            $table->unsignedDouble('nearest_cinema_hall');

            $table->timestamps();

            $table->foreign('location')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charging_stations');
    }
};
