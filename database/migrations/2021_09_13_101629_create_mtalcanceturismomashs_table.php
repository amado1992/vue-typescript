<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtalcanceturismomashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtalcanceturismomashs', function (Blueprint $table) {
            $table->id();
            //Registro
            $table->string('numeroRegistro');
            $table->boolean('autodiagnostico');
            $table->boolean('capacitacionInicial');
            $table->date('fechaEntregaMintur');
            $table->integer('instalacion_id')->unsigned();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('proceso_id')->constrained('mtprocesosturismomashigienicoseguro')->onDelete('cascade')->onUpdate('cascade');

            $table->string('agrupacion');
            $table->integer('alcance')->nullable();
            $table->boolean('incluyeAlcance');//Si se incluye o no la instalación definida en el valor del alcance

            //Evaluacion
            $table->date('fechaPrevistaEvaluacion')->nullable();
            $table->date('fechaPrimeraEvaluacion')->nullable();
            $table->date('fechaSegundaEvaluacion')->nullable();

            //Certificado
            $table->date('fechaOtorgado')->nullable();
            $table->date('fechaDenegado')->nullable();
            $table->string('requisitoIncumplido')->nullable();

            //Renovacion
            $table->date('fechaRenovadoCertificado')->nullable();
            $table->text('observacion')->nullable();
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
        Schema::dropIfExists('mtalcanceturismomashs');
    }
}
