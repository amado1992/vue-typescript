<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTEmpresasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtempresas', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('empresa');
      $table->string('importadora');
      $table->string('mixta');
      $table->integer('osde_id')->unsigned();
      $table->foreign('osde_id')->on('mtosde')->references('id');
      $table->integer('provincia_id')->unsigned();
      $table->foreign('provincia_id')->on('mtprovincia')->references('id');
      $table->string('direccion');
      $table->string('correo');
      $table->integer('telefono');
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
    Schema::dropIfExists('mtempresas');
  }
}
