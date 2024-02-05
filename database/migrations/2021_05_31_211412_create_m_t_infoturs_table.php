<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTInfotursTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtinfoturs', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('infotur');
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
    Schema::dropIfExists('mtinfoturs');
  }
}
