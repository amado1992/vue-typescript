<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTOficinaEmpleosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtoficina_empleos', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('oficina_empleo');
      $table->string('direccion');
      $table->bigInteger('delegacion_territorial_id')->unsigned();
      $table->foreign('delegacion_territorial_id')->on('mtdelegacion_territorials')->references('id');
      $table->integer('telefono');
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
    Schema::dropIfExists('mtoficina_empleos');
  }
}
