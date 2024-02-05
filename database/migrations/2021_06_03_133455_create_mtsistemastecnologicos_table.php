<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtsistemastecnologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtsistemastecnologicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('mtequipos')->onDelete('cascade')->onUpdate('cascade');
            //$table->foreignId('reportSistemaTecnolog_id')->constrained('mtreportsistemastecnologicos')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('esContratadoMantenimiento');
            $table->integer('totalEqInstalado');
            $table->double('totalHrTrabajarEqInst');//calculate
            $table->double('totalHrDejadaTrabjEqInst');//calculate
            $table->double('coeficienteDispTecnica');//calculate
            $table->integer('mantenimientoReparacion')->nullable();
            $table->integer('reparacionCapital')->nullable();
            $table->integer('reposicion')->nullable();
            $table->text('observacion')->nullable();

            $table->date('fechaReporte');
            $table->integer('instalacion_id')->unsigned();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mtsistemastecnologicos');
    }
}
