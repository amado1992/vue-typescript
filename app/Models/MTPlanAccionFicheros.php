<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTPlanAccionFicheros extends Model
{
    public $table = 'mtplanaccion_elementos_ehe';

    public $guarded  = [];

    protected $casts = [
        //
    ];

    public static $rules = [
        //
    ];

    public function fichero_planaccion()
    {
        return $this->belongsTo(MTEventoHigienicoEpidemiologico::class, 'ehe_id');
    }
}
