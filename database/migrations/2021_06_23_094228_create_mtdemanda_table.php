<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtdemandaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtdemanda', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('instalacion_id')->unsigned();
            $table->integer('familia_id')->unsigned();
            $table->integer('producto_id')->unsigned();
            $table->string('unidad_medida');
            $table->integer('enero')->unsigned();
            $table->integer('febrero')->unsigned();
            $table->integer('marzo')->unsigned();
            $table->integer('abril')->unsigned();
            $table->integer('mayo')->unsigned();
            $table->integer('junio')->unsigned();
            $table->integer('julio')->unsigned();
            $table->integer('agosto')->unsigned();
            $table->integer('septiembre')->unsigned();
            $table->integer('octubre')->unsigned();
            $table->integer('noviembre')->unsigned();
            $table->integer('diciembre')->unsigned();
            $table->integer('anno');
            $table->integer('total_demanda_anual')->unsigned();
            $table->integer('aprobado')->unsigned()->nullable();
            $table->integer('deficit')->unsigned()->nullable();
            $table->float('porciento_aprobado', 10, 3)->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id')->onDelete('cascade');
            $table->foreign('familia_id')->on('familia')->references('id')->onDelete('cascade');
            $table->foreign('producto_id')->on('producto')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtdemanda');
    }
}
