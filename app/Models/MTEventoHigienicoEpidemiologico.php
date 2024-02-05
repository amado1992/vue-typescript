<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEventoHigienicoEpidemiologico extends Model
{
    public $table = 'mtevento_higienico_epidemiologico';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $guarded  = ['id'];

    protected $casts = [
        'id' => 'integer',
        'cod_registro' => 'string',
        'fecha_registro' => 'date',
        'fecha_deteccion' => 'date',
        'deteccion' => 'string',
        'detectado_por' => 'string',
        'instalacion_id' => 'integer',
        'clasificacion_evento' => 'string',
        'tipo_foco' => 'string',
        'cant_focos' => 'integer',
        'deposito_foco' => 'string',
        'ubicacion_foco' => 'string',
        'tipo_muestra' => 'string',
        'patogeno_identificado' => 'string',
        'ubicacion_muestra' => 'string',
        'tipo_caso' => 'string',
        'origen_caso' => 'string',
        'agente_causal_caso' => 'string',
        'mecanismo_transmision_brote' => 'string',
        'vehiculo_tipo_caso' => 'string',
        'alimento_involucrado_tipo_caso' => 'string',
        'tipo_brote' => 'string',
        'origen_brote' => 'string',
        'agente_causal_brote' => 'string',
        'mecanismo_transmision_tipo_caso' => 'string',
        'vehiculo_brote' => 'string',
        'alimento_involucrado_brote' => 'string',
        'estado_proceso' => 'string',
        'expuestos' => 'integer',
        'afectados' => 'integer',
        'ingresados' => 'integer',
        'fallecidos' => 'integer',
        'plan_accion' => 'boolean',
        'medida_disciplinaria' => 'integer',
        'naturaleza_medida_disciplinaria' => 'string',
        'seguimiento_evento' => 'boolean',
        'tipo_seguimiento' => 'string',
        'fecha_seguimiento_evento' => 'date',
        'ejecutado_por' => 'string',
        'descripcion' => 'string',
        'resultado_seguimiento_evento' => 'string',
        'informe_conclusivo' => 'boolean',
        'fecha_cierre' => 'date',
    ];

    public static $rules = [
        'fecha_registro'  => 'date',
        'fecha_deteccion'  => 'bail|required|date|before_or_equal:now',
        'deteccion' => 'required',
        'detectado_por' => 'bail|required_if:deteccion,==,Externa|nullable',
        'expuestos' => 'bail|required|integer',
        'afectados' => 'bail|required|integer',
        'ingresados' => 'bail|required|integer',
        'fallecidos' => 'bail|required|integer',
        'plan_accion' => 'required',
        'seguimiento_evento' => 'required',
        'tipo_foco' => 'bail|required_if:clasificacion_evento,==,Foco|nullable',
        'cant_focos' => 'bail|required_if:clasificacion_evento,==,Foco|nullable|integer|gt:0',
        'deposito_foco' => 'bail|required_if:clasificacion_evento,==,Foco|nullable',
        'ubicacion_foco' => 'bail|required_if:clasificacion_evento,==,Foco|nullable',
        'tipo_muestra' => 'bail|required_if:clasificacion_evento,==,Muestra Positiva|nullable',
        'patogeno_identificado' => 'bail|required_if:clasificacion_evento,==,Muestra Positiva|nullable',
        'ubicacion_muestra' => 'bail|required_if:clasificacion_evento,==,Muestra Positiva|nullable',
        'tipo_caso' => 'bail|required_if:clasificacion_evento,==,Caso|nullable',
        'origen_caso' => 'bail|required_if:tipo_caso,==,Enfermedad de Transmisión Alimentaria|nullable',
        'mecanismo_transmision_tipo_caso' => 'bail|required_if:clasificacion_evento,==,Caso|nullable',
        'vehiculo' => 'bail|required_if:mecanismo_transmision_tipo_caso,==,Indirecta|nullable',
        'alimento_involucrado' => 'bail|required_if:vehiculo,==,Alimentos|nullable',
        'tipo_brote' => 'bail|required_if:clasificacion_evento,==,Brote|nullable',
        'origen_brote' => 'bail|required_if:tipo_brote,==,Enfermedad de Transmisión Alimentaria|nullable',
        'agente_causal_brote' => 'bail|required_if:clasificacion_evento,==,Brote|nullable',
        'mecanismo_transmision_brote' => 'bail|required_if:clasificacion_evento,==,Brote|nullable',
        'vehiculo_brote' => 'bail|required_if:mecanismo_transmision_brote,==,Indirecta|nullable',
        'alimento_involucrado_brote' => 'bail|required_if:vehiculo_brote,==,Alimentos|nullable',
        'medida_disciplinaria' => 'bail|integer|gt:0',
        'naturaleza_medida_disciplinaria' => 'bail|required_if:vehiculo_brote,==,Alimentos|nullable',
        'tipo_seguimiento' => 'bail|required_if:seguimiento_evento,==,true|nullable',
        'fecha_seguimiento_evento' => 'bail|required_if:seguimiento_evento,==,true|nullable|date',
    ];

    public function instalaciones()
    {
        return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
    }
    public function planaccion_elementos()
    {
        return $this->hasMany(MTPlanAccionFicheros::class, 'ehe_id');
    }
    public function resumen_documentos()
    {
        return $this->hasMany(MTPlanAccionFicheros::class, 'ehe_id');
    }
}
