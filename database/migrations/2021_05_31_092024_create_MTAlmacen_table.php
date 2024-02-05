<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTAlmacenTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtalmacen', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nombre');
      $table->string('codigo')->nullable();
      $table->string('direccion');
      $table->float('latitud');
      $table->float('longitud');
      // Dimensiones
      $table->float('largo');
      $table->float('ancho');
      $table->float('puntal_libre');
      $table->float('altura_prom_estiba');
      $table->float('area_util');
      $table->float('volumen_util');
      // Equipamiento
      $table->string('cant_montacargas');
      $table->string('cant_transpaletas');
      $table->string('cant_esteras');
      $table->string('cant_basculas');
      // Medio de almacenamiento
      $table->string('cant_estantes');
      $table->string('cant_paletas');
      $table->string('cant_cajas_paletas');
      // Estado constructivo
      $table->string('estado_constructivo');
      $table->string('estado_estructura');
      $table->string('estado_paredes');
      $table->string('estado_piso');
      $table->string('estado_puertas');
      $table->string('estado_ventanas');
      $table->string('estado_techo');
      // Ventilacion
      $table->string('ventilacion_natural');
      $table->string('ventilacion_artificial');
      // Iluminacion
      $table->string('iluminacion_natural');
      $table->string('iluminacion_artificial');
      // Proteccion
      $table->string('prot_contra_intrusos');
      $table->string('prot_contra_incendios');
      $table->text('prot_observaciones')->nullable();
      // Inversion y mantenimiento
      $table->string('plan_inversiones');

      $table->integer('instalacion_id')->unsigned();
      $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');

      $table->integer('categoria_id')->unsigned();
      $table->foreign('categoria_id')->on('mtcategoria')->references('id');

      $table->integer('distribucion_id')->unsigned();
      $table->foreign('distribucion_id')->on('mtdistribucion')->references('id');

      $table->integer('tipo_almacen_id')->unsigned();
      $table->foreign('tipo_almacen_id')->on('mttipoalmacen')->references('id');

      $table->integer('actividad_id')->unsigned();
      $table->foreign('actividad_id')->on('mtactividad')->references('id');

      $table->integer('temperatura_id')->unsigned();
      $table->foreign('temperatura_id')->on('mttemperatura')->references('id');

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
    Schema::dropIfExists('mtalmacen');
  }
}
