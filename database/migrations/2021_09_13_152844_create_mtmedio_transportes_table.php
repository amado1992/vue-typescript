<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTmedioTransportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtmedio_transportes', function (Blueprint $table) {
            $table->id();
            $table->integer('instalacion_id')->unsigned()->nullable();
            $table->foreign('instalacion_id')
                ->references('id')
                ->on('mtinstalacion');
            $table->date('fecha')->required();
            $table->string('tipo_flota')->required();
            $table->string('tipo_medio_transporte')->required();
            $table->string('marca')->required();
            $table->string('modelo')->required();
            $table->string('color')->required();
            $table->string('estado_tecnico')->required();
            $table->boolean('electrico')->default(false);
            $table->string('tipo_combustible')->nullable();
            $table->string('num_motor')->required();
            $table->string('situacion_actual')->required();
            $table->string('motivo_paralizado')->nullable();
            $table->string('clase')->required();
            $table->string('matricula')->required();
            $table->string('num_carroceria')->required();
            $table->integer('anno_fabricacion')->required();
            $table->integer('edad')->required();
            $table->integer('capacidad_carga')->required();
            $table->string('capacidad_carga_um')->nullable();
            $table->integer('neumaticos')->required();
            $table->integer('indice_consumo')->nullable();
            $table->integer('combustible_asignado')->required();
            $table->date('fecha_ficav')->required();
            $table->string('asignado_cargo')->required();
            $table->string('asignado_nombre_apellidos')->nullable();
            $table->integer('folio')->nullable();
            $table->string('ubicacion_motor')->nullable();
            $table->integer('moto_horas')->nullable();
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
        Schema::dropIfExists('mtmedio_transportes');
    }
}
