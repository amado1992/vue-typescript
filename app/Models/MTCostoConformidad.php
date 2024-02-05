<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCostoConformidad extends Model
{
    protected $table = "mtcostosconformidades"; //nombre de la tabla
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

    public function costo_calidad_conformidades()
    {
        return $this->hasMany('App\Models\MTCostoCalidadConformidad', 'conformidad_id');
    }
}
