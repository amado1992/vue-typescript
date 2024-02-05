<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtprocesosturismomashigienicoseguroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtprocesosturismomashigienicoseguro', function (Blueprint $table) {
            $table->id();
           //Registro
            $table->string('numeroRegistro');
            $table->boolean('autodiagnostico');
            $table->boolean('capacitacionInicial');
            $table->date('fechaEntregaMintur');
            $table->integer('instalacion_id')->unsigned();
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->string('agrupacion');
            $table->integer('alcance');
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

           /* //DICTAMEN
            //Evaluacion para: Campos de tipo checkbox que apareceran seleccionados de forma automatica de acuerdo a ciertas condiciones
            $table->boolean('evaluacionInicial')->nullable();
            $table->boolean('seguimientoSemestral')->nullable();
            $table->boolean('renovacion')->nullable();

            //Primera evaluacion
            $table->text('descripcion')->nullable();//Required. Breve descripcion de la instalación
            $table->text('aplicacionListaChequeo')->nullable();//Required
            $table->integer('puntuacionObtenida')->nullable();//Required
            $table->string('requisitoIncumplidoPrimeraEval')->nullable();
            $table->integer('cantidadPuntosCritico')->nullable();//Optional

            $table->text('observacionPrimeraEval')->nullable();//Optional. Observaciones y/o sugerencias: Opcional. Campo de texto de tipo grande.
            $table->text('examenEscrito')->nullable();////Required. Aplicación del examen escrito: Requerido. Campo de texto. Resumen y valoración de los resultados de los exámenes escritos.

            $table->text('preguntaOralConocimiento')->nullable();//Optional.

            $table->integer('personalEvaluar')->nullable();
            $table->integer('personalEvaluado')->nullable();
            $table->integer('porcientoPersonalEvaluado')->nullable();

            $table->integer('aprobado')->nullable();
            $table->integer('porcientoAprobadoEvaluado')->nullable();

            $table->integer('suspenso')->nullable();
            $table->integer('porcientoSuspensoEvaluado')->nullable();

            //Segunda evaluacion
            $table->integer('puntuacionObtenidaSegundaEval')->nullable();//Required
            $table->string('requisitoIncumplidoSegundaEval')->nullable();
            $table->integer('cantidadPuntosCriticoSegundaEval')->nullable();//Optional
            $table->text('observacionSegundaEval')->nullable();*/

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
        Schema::dropIfExists('mtinstalacionescertificadas');
    }
}
