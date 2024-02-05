<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCostoCalidadReport extends Model
{
    protected $table = "mtcostocalidadreportes"; //nombre de la tabla
    protected $fillable = [
        'codigo',
        'nombre',
        'fecha',
        'ventaNetaImporte',
        'ventaNetaAcumulada',
        'instalacion_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'fecha' => 'string',
        'ventaNetaImporte' => 'float',
        'ventaNetaAcumulada' => 'float',
        'instalacion_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'nombre' => 'required',
        'fecha' => 'required',
        'ventaNetaImporte' => 'required',
        'ventaNetaAcumulada' => 'required',
        'instalacion_id' => 'required'
    ];

    public function costo_calidad_no_conformidades()
    {
        return $this->hasMany('App\Models\CostoCalidadModels\MTCostoCalidadNoConformidad', 'reportCostoCalidad_id');
    }

    public function costo_calidad_conformidades()
    {
        return $this->hasMany('App\Models\MTCostoCalidadConformidad', 'reportCostoCalidad_id');
    }

    public function instalacion()
    {
        return $this->belongsTo('App\Models\MTInstalacion', 'instalacion_id');
    }
}
