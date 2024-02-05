<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTUnidadAdministrativasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtunidad_administrativas', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('nombre');
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
    Schema::dropIfExists('mtunidad_administrativas');
  }
}
