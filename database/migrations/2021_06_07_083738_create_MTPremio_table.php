<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTPremioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtpremio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->date('fecha');
            $table->integer('categoriapremio_id')->unsigned();
            $table->foreign('categoriapremio_id')->on('mtcategoriapremio')->references('id');
            $table->integer('instalacion_id')->unsigned();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');
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
        Schema::dropIfExists('mtpremio');
    }
}
