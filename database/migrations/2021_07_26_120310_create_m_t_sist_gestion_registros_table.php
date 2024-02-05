<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTSistGestionRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtsistgestionregistros', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('instalacion_id');
          $table->string('estado');
          $table->integer('tipoSistGest_id');
          $table->integer('operadora_id');
          $table->date('diagnostico_FI')->nullable();
          $table->date('diagnostico_FC')->nullable();
          $table->date('formacion_FI')->nullable();
          $table->date('formacion_FC')->nullable();
          $table->date('disenno_FI')->nullable();
          $table->date('disenno_FC')->nullable();
          $table->date('implementacion_FI')->nullable();
          $table->date('implementacion_FC')->nullable();
          $table->json('auditoria_F')->nullable();
          $table->json('direccion_F')->nullable();
          $table->text('mejora')->nullable();
          $table->date('aval_entidad')->nullable();
          $table->boolean('inclusion')->nullable();
          $table->date('conciliacion_demanda')->nullable();
          $table->date('ratificacion_demanda')->nullable();
          $table->date('firma_contrato')->nullable();
          $table->date('fase1_auditoriaI')->nullable();
          $table->date('fase1_auditoriaC')->nullable();
          $table->date('fase2_auditoriaI')->nullable();
          $table->date('fase2_auditoriaC')->nullable();
          $table->date('auditoria_adicionalI')->nullable();
          $table->date('auditoria_adicionalC')->nullable();
          $table->string('certificado_otorgado')->nullable();
          $table->string('certificado_otorgadofecha')->nullable();
          $table->text('alcance_certificacion')->nullable();
          $table->date('validez_hasta')->nullable();
          $table->date('certificado_denegado')->nullable();
          $table->text('motivo_denegacion')->nullable();
          $table->json('auditorias_seg_anual')->nullable();
          $table->date('auditorias_seg_extraordinario')->nullable();
          $table->date('cancelacion_certificado')->nullable();
          $table->string('motivo_denegacion2')->nullable();
          $table->date('aval_entidadR')->nullable();
          $table->boolean('inclusionR')->nullable();
          $table->date('conciliacion_demandaR')->nullable();
          $table->date('ratificacion_demandaR')->nullable();
          $table->date('firma_contratoR')->nullable();
          $table->date('fase1_auditoriaIR')->nullable();
          $table->date('fase1_auditoriaCR')->nullable();
          $table->date('fase2_auditoriaIR')->nullable();
          $table->date('fase2_auditoriaCR')->nullable();
          $table->date('auditoria_adicionalIR')->nullable();
          $table->date('auditoria_adicionalCR')->nullable();
          $table->string('certificado_otorgadoR')->nullable();
          $table->string('certificado_otorgadofechaR')->nullable();
          $table->text('alcance_certificacionR')->nullable();
          $table->date('validez_hastaR')->nullable();
          $table->date('certificado_denegadoR')->nullable();
          $table->text('motivo_denegacionR')->nullable();
          $table->json('auditorias_seg_anualR')->nullable();
          $table->date('auditorias_seg_extraordinarioR')->nullable();
          $table->date('cancelacion_certificadoR')->nullable();
          $table->string('motivo_denegacion2R')->nullable();
          $table->integer('demanda_servicio_id')->nullable();
          $table->string('alcance')->nullable();
          $table->integer('cant_trabajadores')->nullable();
          $table->boolean('gestionado')->nullable();
          $table->date('fecha_auditoria')->nullable();
          $table->boolean('conciliacion')->nullable();
          $table->date('fecha_conciliacion')->nullable();
          $table->string('estado_demanda')->nullable();
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
        Schema::dropIfExists('mtsistgestionregistros');
    }
}
