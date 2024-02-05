<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTOacesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtoaces', function (Blueprint $table) {
      $table->id();
      $table->integer('codigo')->nullable();
      $table->boolean('activo')->default(true);
      $table->string('siglas');
      $table->string('oace');
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
    Schema::dropIfExists('mtoaces');
  }
}
