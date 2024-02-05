<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTInstalacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtinstalacion', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->default(true);
            $table->string('nombre');
            $table->integer('codigo');

            $table->integer('provincia_id')->unsigned();
            $table->foreign('provincia_id')->on('mtprovincia')->references('id');

            $table->integer('osde_id')->unsigned();
            $table->foreign('osde_id')->on('mtosde')->references('id');

            $table->string('complejo')->nullable();

            $table->integer('tipoInst_id')->unsigned();
            $table->foreign('tipoInst_id')->on('mttipoInst')->references('id');

            $table->integer('categoria_id')->nullable();
            //$table->integer('categoria_id')->unsigned();
            //$table->foreign('categoria_id')->on('MTCategoria')->references('id');

            $table->integer('cadHotelera_id')->nullable();
            //$table->integer('cadHotelera_id')->unsigned();
            //$table->foreign('cadHotelera_id')->on('MTCadena_hoteleras')->references('id');

            $table->integer('tomo');
            $table->integer('folio');
            $table->integer('registro')->nullable();
            $table->date('fecha_registro')->nullable();
            $table->string('direccion');
            $table->string('correo_electronico');
            $table->string('telefono');
            $table->string('mixta');
            $table->string('logo');

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
        Schema::dropIfExists('MTInstalacion');
    }
}
