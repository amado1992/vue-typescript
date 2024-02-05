<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTSistGestionDemandaServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtsistgestiondemandaservicios', function (Blueprint $table) {
            $table->id();
            $table->string('cod_DemandaServicio');
            $table->string('desc_DemandaServicio');
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
        Schema::dropIfExists('mtsistgestiondemandaservicios');
    }
}
