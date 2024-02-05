<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtcostocalidadconformidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtcostocalidadconformidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conformidad_id')->constrained('mtcostosconformidades')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mtcostocalidadconformidades');
    }
}
