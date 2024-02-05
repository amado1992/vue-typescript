<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTMedioTransporte extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'mtmedio_transportes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $guarded  = []; // todos los atributos son $fillable true.

    protected $casts = [
        'id' => 'integer',
        'instalacion_id' => 'integer',
        'tipo_flota' => 'string',
        'tipo_medio_transporte' => 'string',
        'marca' => 'string',
        'modelo' => 'string',
        'color' => 'string',
        'estado_tecnico' => 'string',
        'electrico' => 'boolean',
        'tipo_combustible' => 'string',
        'num_motor' => 'string',
        'situacion_actual' => 'string',
        'motivo_paralizado' => 'string',
        'clase' => 'string',
        'matricula' => 'string',
        'num_carroceria' => 'string',
        'anno_fabricacion' => 'integer',
        'edad' => 'integer',
        'capacidad_carga' => 'integer',
        'capacidad_carga_um' => 'string',
        'neumaticos' => 'integer',
        'indice_consumo' => 'integer',
        'combustible_asignado' => 'integer',
        'fecha_ficav' => 'date',
        'asignado_cargo' => 'string',
        'asignado_nombre_apellidos' => 'string',
        'folio' => 'integer',
        'ubicacion_motor' => 'string',
        'moto_horas' => 'integer',
    ];

    public static $rules = [
        'tipo_flota' => 'required',
        'tipo_medio_transporte' => 'required',
        'marca' => 'required',
        'modelo' => 'required|alpha_num',
        'color' => 'required',
        'estado_tecnico' => 'required',
        'electrico' => 'boolean',
        'num_motor' => 'required|alpha_num',
        'situacion_actual' => 'required',
        'motivo_paralizado' => 'required_if:situacion_actual,==,Paralizado',
        'clase' => 'required',
        'matricula' => 'required|alpha_num',
        'num_carroceria' => 'required|alpha_num',
        'anno_fabricacion' => 'required',
        'capacidad_carga' => 'required',
        'neumaticos' => 'required',
        'indice_consumo' => 'required_if:electrico,false',
        'combustible_asignado' => 'required',
        'fecha_ficav' => 'required',
        'asignado_cargo' => 'required',
        'asignado_nombre_apellidos' => 'string',
        'folio' => 'required_if:tipo_flota,==,Embarcaciones o medios náuticos',
        'ubicacion_motor' => 'required_if:tipo_flota,==,Embarcaciones o medios náuticos',
        'moto_horas' => 'required_if:tipo_flota,==,Embarcaciones o medios náuticos',
    ];

    public function instalaciones()
    {
        return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
    }

    public function historicos()
    {
        return $this->hasMany(MTHistoricoVehiculo::class, 'vehiculo_id');
    }
}
