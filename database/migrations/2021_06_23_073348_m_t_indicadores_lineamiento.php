<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MTIndicadoresLineamiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtindicadores_lineamiento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mes')->unsigned();
            $table->integer('anno')->unsigned();
            $table->integer('instalacion_id')->unsigned();
            $table->integer('licsanitaria_id')->unsigned();
            $table->integer('avalcitma_id')->unsigned();
            $table->integer('avalapci_id')->unsigned();
            $table->timestamps();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');
            $table->foreign('licsanitaria_id')->on('mtlic_sanitaria_nomenclador')->references('id');
            $table->foreign('avalcitma_id')->on('mtaval_citma_nomenclador')->references('id');
            $table->foreign('avalapci_id')->on('mtaval_apci_nomenclador')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtindicadores_lineamiento');
    }
}
