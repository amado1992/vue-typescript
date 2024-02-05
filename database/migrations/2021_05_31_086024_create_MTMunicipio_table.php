<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTMunicipioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtmunicipio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');

            $table->integer('provincia_id')->unsigned();
            $table->foreign('provincia_id')->on('mtprovincia')->references('id');

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
        Schema::dropIfExists('mtmunicipio');
    }
}
