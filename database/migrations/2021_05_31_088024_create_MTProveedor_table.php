<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtproveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('nombre');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->on('mtmunicipio')->references('id');
            $table->integer('tipo_proveedor_id')->unsigned();
            $table->foreign('tipo_proveedor_id')->on('mttipoproveedor')->references('id');
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
        Schema::dropIfExists('mtproveedor');
    }
}
