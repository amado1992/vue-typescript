<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtcostocalidadreportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtcostocalidadreportes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->date('fecha');
            $table->decimal('ventaNetaImporte', 50);
            $table->decimal('ventaNetaAcumulada', 50);
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
        Schema::dropIfExists('mtcostocalidadreportes');
    }
}
