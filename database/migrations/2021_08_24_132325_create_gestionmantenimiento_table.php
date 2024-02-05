<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionmantenimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtgestionmantenimiento', function (Blueprint $table) {
          $table->increments('id');
          $table->string('descripcion');
          $table->integer('instalacion_id');
          $table->integer('anno');
          $table->integer('mes');
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
        Schema::dropIfExists('mtgestionmantenimiento');
    }
}
