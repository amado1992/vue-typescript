<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtseguimientosalcanceturismomashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtseguimientosalcanceturismomashs', function (Blueprint $table) {
            $table->id();
            $table->date('fechaPrimeraEvaluacion')->nullable();
            $table->date('fechaSegundaEvaluacion')->nullable();
            $table->date('fechaSeguimientoMensual')->nullable();//Seguimiento aleatorio mensual a los puntos críticos
            $table->date('fechaSeguimientoSemestral')->nullable();
            $table->date('fechaSuspensionTemporalCertificado')->nullable();
            $table->date('fechaRetiroSuspensionTemporalCertificado')->nullable();
            $table->date('fechaCanceladoCertificado')->nullable();
            $table->string('requisitoIncumplido')->nullable();
            $table->foreignId('mtalcance_id')->constrained('mtalcanceturismomashs')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('mtseguimientosalcanceturismomashs');
    }
}
