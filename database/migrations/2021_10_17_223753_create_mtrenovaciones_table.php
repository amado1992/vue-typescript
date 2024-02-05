<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtrenovacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtrenovaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fechaPrimeraEvaluacion')->nullable();
            $table->date('fechaSegundaEvaluacion')->nullable();
            $table->date('fechaRenovar')->nullable();
            $table->date('fechaSuspensionTemporalCertificado')->nullable();
            $table->date('fechaCanceladoCertificado')->nullable();
            $table->string('requisitoIncumplido')->nullable();
            $table->string('observacion')->nullable();

            $table->foreignId('proceso_id')->constrained('mtprocesosturismomashigienicoseguro')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('mtrenovaciones');
    }
}
