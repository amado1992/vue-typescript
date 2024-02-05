<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTCategoriaInstalacionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mtcategoria_instalacions', function (Blueprint $table) {
      $table->id();
      $table->boolean('activo')->default(true);
      $table->string('categoria_instalacion');
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
    Schema::dropIfExists('mtcategoria_instalacions');
  }
}
