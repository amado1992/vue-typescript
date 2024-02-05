<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTCompraTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtcompra', function (Blueprint $table) {
      $table->increments('id');
      $table->date('fecha_cierre');
      $table->integer('cantidad_real');
      $table->integer('cantidad_plan');
      $table->float('precio');
      $table->double('inventario');
      $table->float('total_compras')->nullable();
      $table->float('compras_nacionales')->nullable();

      $table->integer('instalacion_id')->unsigned();
      $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');

      $table->integer('producto_id')->unsigned();
      $table->foreign('producto_id')->on('producto')->references('id');

      $table->integer('proveedor_id')->unsigned();
      $table->foreign('proveedor_id')->on('mtproveedor')->references('id');

      $table->integer('unidadmedida_id')->unsigned();
      $table->foreign('unidadmedida_id')->on('mtunidadmedida')->references('id');

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
    Schema::dropIfExists('mtcompra');
  }
}
