<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSeguimientoAlcanceTurismoMasHS extends Model
{
    public $table = 'mtseguimientosalcanceturismomashs';

    public $fillable = [
        'fechaPrimeraEvaluacion',
        'fechaSegundaEvaluacion',
        'fechaSeguimientoMensual',
        'fechaSeguimientoSemestral',
        'fechaSuspensionTemporalCertificado',
        'fechaRetiroSuspensionTemporalCertificado',
        'fechaCanceladoCertificado',
        'requisitoIncumplido',
        'mtalcance_id'
    ];

    public function alcance_turismo_mas_higienico_seguro()
    {
        return $this->belongsTo('App\Models\MTAlcanceTurismoMasHS', 'mtalcance_id');
    }
}
