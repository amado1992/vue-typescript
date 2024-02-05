<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTPlanRecape extends Model
{
    public $table = 'mtplan_recape';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'instalacion_id',
        'fecha',
        'potencial',
        'anno',
        'plan_recape',
        'observaciones',
    ];

    protected $casts = [
        'instalacion_id' => 'integer',
        'fecha' => 'date',
        'potencial' => 'integer',
        'anno' => 'integer',
        'plan_recape' => 'integer',
        'observaciones' => 'string',
    ];

    public static $rules = [
        'instalacion_id' => 'integer',
        'potencial' => 'required|integer',
    ];

    public function instalaciones()
    {
        return $this->belongsTo(\App\Models\MTInstalacion::class, 'instalacion_id');
    }

    public function actualizaciones()
    {
        return $this->hasMany(MTActualizacionesPlanRecape::class, 'plan_id');
    }
}
