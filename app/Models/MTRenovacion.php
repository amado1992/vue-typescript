<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTRenovacion extends Model
{
    public $table = 'mtrenovaciones';

    public $fillable = [
        'fechaPrimeraEvaluacion',
        'fechaSegundaEvaluacion',
        'fechaRenovar',
        'fechaSuspensionTemporalCertificado',
        'observacion',
        'fechaCanceladoCertificado',
        'requisitoIncumplido',
        'proceso_id'
    ];

    public function proceso_turismo_mas_higienico_seguro()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }
}
