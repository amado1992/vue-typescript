<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSeguimientoTurismoMasHS extends Model
{
    public $table = 'mtseguimientosturismomashs';

    public $fillable = [
        'fechaPrimeraEvaluacion',
        'fechaSegundaEvaluacion',
        'fechaSeguimientoMensual',
        'fechaSeguimientoTrimestral',
        'fechaSuspensionTemporalCertificado',
        'fechaRetiroSuspensionTemporalCertificado',
        'fechaCanceladoCertificado',
        'requisitoIncumplido',
        'proceso_id'
    ];

    public function proceso_turismo_mas_higienico_seguro()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }
}
