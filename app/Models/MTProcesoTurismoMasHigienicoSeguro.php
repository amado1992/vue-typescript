<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTProcesoTurismoMasHigienicoSeguro extends Model
{
    public $table = 'mtprocesosturismomashigienicoseguro';

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
       // 'fechaSuspensionTemporalCertificado',
       // 'fechaRetiroSuspensionTemporalCertificado',
       // 'fechaCanceladoCertificado',
        //'requisitoIncumplido',
        'fechaRenovadoCertificado',
        'observacion',
        'instalacion_id',

        'agrupacion',
        'alcance',
        'incluyeAlcance'
    ];

    public function instalacion()
    {
        return $this->belongsTo('App\Models\MTInstalacion', 'instalacion_id');
    }

    public function expediente_elementos()
    {
        return $this->hasMany('App\Models\MTExpedienteElemento', 'proceso_id');
    }

    public function seguimientos()
    {
        return $this->hasMany('App\Models\MTSeguimientoTurismoMasHS', 'proceso_id');
    }

    public function alcance_turismo_mas_higienico_seguro()
    {
        return $this->hasMany('App\Models\MTAlcanceTurismoMasHS', 'proceso_id');
    }
}
