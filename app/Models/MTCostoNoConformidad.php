<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCostoNoConformidad extends Model
{
    protected $table = "mtcostosnoconformidades"; //nombre de la tabla
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'tipo' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'nombre' => 'required',
        'tipo' => 'required',
    ];

    public function costo_calidad_no_conformidades()
    {
        return $this->hasMany('App\Models\MTCostoCalidadNoConformidad', 'noconformidad_id');
    }
}
