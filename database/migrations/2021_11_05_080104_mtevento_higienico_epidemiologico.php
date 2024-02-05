<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MteventoHigienicoEpidemiologico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtevento_higienico_epidemiologico', function (Blueprint $table) {
            $table->id();
            $table->string('cod_registro')->required();
            $table->date('fecha_registro')->required();
            $table->date('fecha_deteccion')->required();
            $table->string('deteccion')->required();
            $table->string('detectado_por')->nullable();
            $table->integer('instalacion_id')->unsigned()->nullable();
            $table->foreign('instalacion_id')
                ->references('id')
                ->on('mtinstalacion');
            //$table->string('territorio')->required();
            //$table->string('municipio')->required();
            $table->string('clasificacion_evento');
            $table->string('tipo_foco')->nullable();
            $table->integer('cant_focos')->nullable();
            $table->string('deposito_foco')->nullable();
            $table->string('ubicacion_foco')->nullable();
            $table->string('tipo_muestra')->nullable();
            $table->string('patogeno_identificado_muestra')->nullable();
            $table->string('ubicacion_muestra')->nullable();
            $table->string('tipo_caso')->nullable();
            $table->string('origen_caso')->nullable();
            $table->string('agente_causal_caso')->nullable();
            $table->string('mecanismo_transmision_caso')->nullable();
            $table->string('vehiculo_caso')->nullable();
            $table->string('alimento_involucrado_caso')->nullable();
            $table->string('tipo_brote')->nullable();
            $table->string('origen_brote')->nullable();
            $table->string('agente_causal_brote')->nullable();
            $table->string('mecanismo_transmision_brote')->nullable();
            $table->string('vehiculo_brote')->nullable();
            $table->string('alimento_involucrado_brote')->nullable();
            $table->string('estado_proceso');
            $table->integer('expuestos')->required();
            $table->integer('afectados')->required();
            $table->integer('ingresados')->required();
            $table->integer('fallecidos')->required();
            $table->boolean('plan_accion')->required();
            $table->integer('medida_disciplinaria');
            $table->string('naturaleza_medida_disciplinaria');
            $table->boolean('seguimiento_evento')->required();
            $table->string('tipo_seguimiento')->nullable();
            $table->date('fecha_seguimiento_evento')->nullable();
            $table->string('ejecutado_por')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('resultado_seguimiento_evento')->nullable();
            $table->boolean('informe_conclusivo')->default(false);
            $table->date('fecha_cierre');
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
        Schema::dropIfExists('mtevento_higienico_epidemiologico');
    }
}
