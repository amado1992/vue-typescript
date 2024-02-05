<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTActualizacionesPlanRecape extends Model
{
    public $table = 'mtactualizaciones_plan_recape';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $guarded  = []; // todos los atributos son $fillable true.

    protected $casts = [
        'plan_id' => 'integer',
        'fecha_cumplimiento' => 'date',
        'recapados' => 'integer',
    ];

    public static $rules = [
        'instalacion_id' => 'integer',
        'fecha_cumplimiento' => 'date',
        'recapados' => 'integer',
    ];

    public function planRecape()
    {
        return $this->belongsTo(MTPlanRecape::class, 'plan_id');
    }
}
