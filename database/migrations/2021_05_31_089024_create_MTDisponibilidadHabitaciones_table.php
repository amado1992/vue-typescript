<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTDisponibilidadHabitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtdisponibilidadhabitaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cant_habitaciones_nd')->require();


            $table->integer('entidad_id')->unsigned();
            $table->foreign('entidad_id')->on('mtentidad')->references('id');

            $table->integer('causa_id')->unsigned();
            $table->foreign('causa_id')->on('mtcausa')->references('id');

            $table->integer('clasificacion_id')->unsigned();
            $table->foreign('clasificacion_id')->on('mtclasificacion')->references('id');

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
        Schema::dropIfExists('mtdisponibilidadhabitaciones');
    }
}
