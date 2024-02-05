<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDictamenTMHS extends Model
{
    protected $table = "mtdictamenestmhs"; //nombre de la tabla
    protected $fillable =
       [
        'proceso_id',
        'descripcion',
        'aplicacionListaChequeo',
        'puntuacionObtenida',
        'requisitoIncumplido',
        'cantidadPuntosCritico',
        'observacion',
        'examenEscrito',
        'preguntaOralConocimiento',

        'evaluacionInicial',
        'seguimientoSemestral',
        'renovacion',

       'personalEvaluar',
       'personalEvaluado',
       'porcientoPersonalEvaluado',

       'aprobado',
       'porcientoAprobadoEvaluado',

       'suspenso',
       'porcientoSuspensoEvaluado',

       'puntuacionObtenidaSegundaEval',
       'requisitoIncumplidoSegundaEval',
       'cantidadPuntosCriticoSegundaEval',
       'observacionSegundaEval',

       'otorgadoCertificado',

       'mantenerCertificado',
       'suspenderTemporalCertificado',
       'cancelarCertificado',

       'renovarCertificado',

       'elavoradoPor',
       'nombreApellidos',
       'fechaCierreDictamen',

       'aprobadoDelegadoTerritorialMintur',
       'nombreApellidosDelegadoTerritorio',
       'fechaAprobacionDelegadoTerritorio',

       'aprobadoAutoridadadSanitariaTerritorio',
       'nombreApellidosAutoridadadSanitariaTerritorio',
       'fechaAprobacionAutoridadadSanitariaTerritorio',

       'mttipoeval_id',
       'seguimiento_id',
       'renovacion_id',
       ];//campos que son llenados en el formulario
    public function proceso()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }

    public function seguimiento()
    {
        return $this->belongsTo('App\Models\MTRenovacion', 'seguimiento_id');
    }
    public function renovacion()
    {
        return $this->belongsTo('App\Models\MTSeguimientoTurismoMasHS', 'renovacion_id');
    }

    public function tipo_eval(){

        return $this->belongsTo(MTTipoEvalModel::class,'mttipoeval_id');
    }
}
