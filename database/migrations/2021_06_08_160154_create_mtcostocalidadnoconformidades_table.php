<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtcostocalidadnoconformidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtcostocalidadnoconformidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('noconformidad_id')->constrained('mtcostosnoconformidades')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reportCostoCalidad_id')->constrained('mtcostocalidadreportes')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('importe', 50);
            $table->decimal('acumulado', 50);
            $table->string('tipo');
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
        Schema::dropIfExists('mtcostocalidadnoconformidades');
    }
}
