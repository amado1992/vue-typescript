<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTCadenaHotelerasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtcadena_hoteleras', function (Blueprint $table) {
      $table->id();
      $table->boolean('activo')->default(true);
      $table->string('cadena_hotelera');
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
    Schema::dropIfExists('mtcadena_hoteleras');
  }
}
