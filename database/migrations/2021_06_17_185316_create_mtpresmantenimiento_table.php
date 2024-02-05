<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtpresmantenimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtpresmantenimiento', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('gestionmtto_id');
          $table->string('descMtto');
          $table->string('unidadMedida');
          $table->decimal('prespMttoT1')->nullable();
          $table->decimal('prespMttoT2')->nullable();
          $table->decimal('prespMttoTotal')->nullable();
          $table->decimal('prespMttoC1')->nullable();
          $table->decimal('prespMttoC2')->nullable();
          $table->decimal('prespMttoContrat')->nullable();
          $table->decimal('realMttoT1')->nullable();
          $table->decimal('realMttoT2')->nullable();
          $table->decimal('realMttoTotal')->nullable();
          $table->decimal('realMttoC1')->nullable();
          $table->decimal('realMttoC2')->nullable();
          $table->decimal('realMttoContrat')->nullable();
          $table->decimal('porCientoMttoTot1')->nullable();
          $table->decimal('porCientoMttoTot2')->nullable();
          $table->decimal('porCientoMttoTot3')->nullable();
          $table->decimal('porCientoMttoContrat1')->nullable();
          $table->decimal('porCientoMttoContrat2')->nullable();
          $table->decimal('porCientoMttoContrat3')->nullable();
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
        Schema::dropIfExists('mtpresmantenimiento') ;
    }
}
