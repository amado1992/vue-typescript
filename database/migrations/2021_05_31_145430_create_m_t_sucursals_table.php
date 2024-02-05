<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTSucursalsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtsucursals', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('sucursal');
      $table->string('complejo')->nullable();
      $table->bigInteger('empresa_id')->unsigned();
      $table->foreign('empresa_id')->on('mtempresas')->references('id');
      $table->integer('provincia_id')->unsigned();
      $table->foreign('provincia_id')->on('mtprovincia')->references('id');
      $table->string('direccion');
      $table->string('correo');
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
    Schema::dropIfExists('mtsucursals');
  }
}
