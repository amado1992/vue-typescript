<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDisponibilidadHabitaciones extends Model
{
    public $table = 'mtdisponibilidadhabitaciones';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'cant_habitaciones_nd',
        'entidad_id',
        'causa_id',
        'clasificacion_id'

    ];

    protected $casts = [
        'id' => 'integer',
        'cant_habitaciones_nd' => 'string',
        'entidad_id'=> 'integer',
        'causa_id'=> 'integer',
        'clasificacion_id'=> 'integer'
    ];

    public static $rules = [
        'cant_habitaciones_nd' => 'required',
        'entidad_id'=> 'required',
        'causa_id'=> 'required',
        'clasificacion_id'=> 'required'
    ];


    public function entidades()
    {
        return $this->belongsTo(\App\Models\MTEntidad::class, 'entidad_id');
    }

    public function causas()
    {
        return $this->belongsTo(\App\Models\MTCausa::class, 'causa_id');
    }

    public function clasificaciones()
    {
        return $this->belongsTo(\App\Models\MTClasificacion::class, 'clasificacion_id');
    }

}
