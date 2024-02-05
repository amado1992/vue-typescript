<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAlcanceTurismoMasHS extends Model
{
    public $table = 'mtalcanceturismomashs';

    public $fillable = [
        'numeroRegistro',
        'autodiagnostico',
        'capacitacionInicial',
        'fechaEntregaMintur',
        'fechaPrevistaEvaluacion',
        'fechaPrimeraEvaluacion',
        'fechaSegundaEvaluacion',
        'fechaOtorgado',
        'fechaDenegado',
        'fechaRenovadoCertificado',
        'observacion',
        'instalacion_id',
        'proceso_id',

        'agrupacion',
        'alcance',
        'incluyeAlcance'
    ];

    public function instalacion()
    {
        return $this->belongsTo('App\Models\MTInstalacion', 'instalacion_id');
    }

    public function proceso_turismo_mas_higienico_seguro()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }

    public function alcance_seguimientos()
    {
        return $this->hasMany('App\Models\MTSeguimientoAlcanceTurismoMasHS', 'mtalcance_id');
    }
}
