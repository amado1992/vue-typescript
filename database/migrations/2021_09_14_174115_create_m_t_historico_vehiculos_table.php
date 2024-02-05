<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTHistoricoVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehiculo_id')->unsigned()->nullable();
            $table->foreign('vehiculo_id')
                ->references('id')
                ->on('mtmedio_transportes')
                ->onDelete('cascade');
            $table->string('estado')->required();
            $table->date('estado_fechaInicial')->required();
            $table->date('estado_fechaFinal')->nullable();
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
        Schema::dropIfExists('historico_vehiculos');
    }
}
