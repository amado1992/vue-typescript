<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtosdeTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtosde', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(false);
      $table->string('nombre');
      $table->string('tipo');

      $table->integer('provincia_id')->unsigned();
      $table->foreign('provincia_id')->on('mtprovincia')->references('id');

      $table->bigInteger('oace_id')->unsigned();
      $table->foreign('oace_id')->on('mtoaces')->references('id');

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
    Schema::dropIfExists('MTOsde');
  }
}
