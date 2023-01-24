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
        Schema::create('metropolitans', function (Blueprint $table) {
            $table->id();
            $table->string('metropolitan_name');
            $table->integer('wards');
            $table->unsignedBigInteger('district');
            $table->timestamps();

            $table->foreign('district')->references('id')->on('districts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metropolitans');
    }
};
