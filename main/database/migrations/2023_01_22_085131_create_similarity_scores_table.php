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
        Schema::create('similarity_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('charging_station_1');
            $table->unsignedBigInteger('charging_station_2');
            $table->unsignedDouble('similarity_score');
            $table->timestamps();

            $table->foreign('charging_station_1')->references('id')->on('charging_stations');
            $table->foreign('charging_station_2')->references('id')->on('charging_stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('similarity_scores');
    }
};
