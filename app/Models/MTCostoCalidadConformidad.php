<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCostoCalidadConformidad extends Model
{
    protected $table = "mtcostocalidadconformidades";

    const CREATED_AT = 'created_at';

    public $fillable = [
        'conformidad_id',
        'reportCostoCalidad_id',
        'importe',
        'acumulado',
        'tipo'
    ];

    protected $casts = [
        'conformidad_id' => 'integer',
        'reportCostoCalidad_id' => 'integer',
        'importe' => 'integer',
        'acumulado' => 'integer',
        'tipo' => 'string'
    ];

    public static $rules = [
        'conformidad_id' => 'required',
        'reportCostoCalidad_id' => 'required',
        'importe' => 'required',
        'acumulado' => 'required',
        'tipo' => 'required',
    ];

    public function costo_conformidad()
    {
        return $this->belongsTo('App\Models\MTCostoConformidad', 'conformidad_id');
    }

    public function costo_calidad_report()
    {
        return $this->belongsTo('App\Models\MTCostoCalidadReport', 'reportCostoCalidad_id');
    }
}
