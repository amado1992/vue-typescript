<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtdictamenestmhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtdictamenestmhs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proceso_id')
        ->nullable()
        ->constrained('mtprocesosturismomashigienicoseguro')->onDelete('cascade')->onUpdate('cascade');

    $table->foreignId('seguimiento_id')
        ->nullable()
        ->constrained('mtseguimientosturismomashs')->onDelete('cascade')->onUpdate('cascade');

     $table->foreignId('renovacion_id')
         ->nullable()
         ->constrained('mtrenovaciones')->onDelete('cascade')->onUpdate('cascade');

    $table->text('descripcion')->nullable();//Required. Breve descripcion de la instalación

    //RESULTADOS DE LA EVALUACIÓN.
    //Primera Evaluacion
    //Evaluacion para: Campos de tipo checkbox que apareceran seleccionados de forma automatica de acuerdo a ciertas condiciones
    $table->boolean('evaluacionInicial');
    $table->boolean('seguimientoSemestral');
    $table->boolean('renovacion');

    //El sistema debe permitir definir dos fechas de evaluación:
    $table->date('fechaPrimeraEvaluacion')->nullable();
    $table->date('fechaSegundaEvaluacion')->nullable();

    $table->text('aplicacionListaChequeo')->nullable();//Required
    $table->integer('puntuacionObtenida');//Required
    $table->string('requisitoIncumplido')->nullable();
    $table->integer('cantidadPuntosCritico');//Optional

    $table->text('observacion')->nullable();//Optional. Observaciones y/o sugerencias: Opcional. Campo de texto de tipo grande.
    $table->text('examenEscrito')->nullable();//Required. Aplicación del examen escrito: Requerido. Campo de texto. Resumen y valoración de los resultados de los exámenes escritos.

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
    $table->text('observacionSegundaEval')->nullable();

    //PROPUESTA DEL EQUIPO EVALUADOR
    //Primera Evaluacion
    //Etapa evaluación inicial
    $table->boolean('otorgadoCertificado')->nullable();

    //Seguimiento Semestral
    $table->boolean('mantenerCertificado')->nullable();
    $table->boolean('suspenderTemporalCertificado')->nullable();
    $table->boolean('cancelarCertificado')->nullable();

    //Renovacion
    $table->boolean('renovarCertificado')->nullable();
   // Renovar Certificado, Suspender temporalmente Certificado, Cancelar Certificado.

    $table->string('elavoradoPor')->nullable();//Grupo evaluador
    $table->string('nombreApellidos')->nullable();//Nombre y apellidos: Usuario que elabora el dictamen

    $table->date('fechaCierreDictamen')->nullable();//Esta fecha se toma automático del sistema cuando se cierra el    dictamen de evaluación.
    //FIN PROPUESTA DEL EQUIPO EVALUADOR

    //APROBADO POR
    $table->boolean('aprobadoDelegadoTerritorialMintur')->nullable(); //Revisado por: (Delegado Territorial del Mintur)
    $table->string('nombreApellidosDelegadoTerritorio')->nullable();
    $table->date('fechaAprobacionDelegadoTerritorio')->nullable();

    $table->boolean('aprobadoAutoridadadSanitariaTerritorio')->nullable(); //Revisado por: (Delegado Territorial del Mintur)
    $table->string('nombreApellidosAutoridadadSanitariaTerritorio')->nullable();
    $table->date('fechaAprobacionAutoridadadSanitariaTerritorio')->nullable();

    $table->foreignId('mttipoeval_id')
                  ->constrained('mttiposevals')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('mtdictamenestmhs');
    }
}
