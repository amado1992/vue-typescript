<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTSistGestionTiposSistGestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtsistgestiontipossistgestion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc_TipoSistGestion');
            $table->string('norma_Referencia');
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
        Schema::dropIfExists('mtsistgestiontipossistgestion');
    }

}
