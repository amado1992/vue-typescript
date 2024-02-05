<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTGestionarAccidentesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtgestionaraccidentes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('instalacion_id')->unsigned()->nullable();
      $table->foreign('instalacion_id')->references('id')->on('mtinstalacion');
      $table->string('radicacion');
      $table->date('fecha');
      $table->string('lugar');
      $table->time('hora');
      $table->boolean('imputable');
      $table->boolean('accidente_via');
      $table->boolean('accidente_base');
      $table->integer('clasificacion_accidente_id')->unsigned();
      $table->foreign('clasificacion_accidente_id')->on('mtclasificacionaccidentes')->references('id');
      $table->integer('victima_accidentes_id')->unsigned()->nullable();
      $table->foreign('victima_accidentes_id')->on('mtvictimaaccidentes')->references('id');
      $table->integer('fallecido')->nullable();
      $table->integer('herido')->nullable();
      $table->float('perdidas_materiales')->nullable();
      $table->bigInteger('vehiculo_empresa_id')->unsigned();
      $table->foreign('vehiculo_empresa_id')->on('mtmedio_transportes')->references('id');
      $table->integer('vehiculo_ajeno_id')->unsigned()->nullable();
      $table->foreign('vehiculo_ajeno_id')->on('mtvehiculoajeno')->references('id');
      $table->integer('causa_accidente_id')->unsigned();
      $table->foreign('causa_accidente_id')->on('mtcausaaccidentes')->references('id');
      $table->integer('annos_experiencia');
      $table->string('nombre_apellidos');
      $table->string('expediente_laboral');
      $table->string('estacion_pnr')->nullable();
      $table->string('tribunal_competente')->nullable();
      $table->integer('atestado')->nullable();
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
    Schema::dropIfExists('mtgestionaraccidentes');
  }
}
