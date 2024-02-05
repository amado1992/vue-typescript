<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTKmRecorridosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtkm_recorridos', function (Blueprint $table) {
      $table->id();
      $table->integer('mes')->unsigned();
      $table->integer('anno')->unsigned();
      $table->float('km_recorridos');
      $table->bigInteger('vehiculo_empresa_id')->unsigned();
      $table->foreign('vehiculo_empresa_id')->on('mtmedio_transportes')->references('id');
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
    Schema::dropIfExists('mtkm_recorridos');
  }
}
