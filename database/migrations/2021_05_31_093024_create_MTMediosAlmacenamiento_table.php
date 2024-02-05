<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTMediosAlmacenamientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtmediosalmacenamiento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('bueno');
            $table->integer('regular');
            $table->integer('malo');

            $table->integer('almacen_id')->unsigned();
            $table->foreign('almacen_id')->on('mtalmacen')->references('id');

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
        Schema::dropIfExists('mtmediosalmacenamiento');
    }
}
