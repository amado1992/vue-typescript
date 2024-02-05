<?php

namespace App\Models\CostoCalidadModels;

use Illuminate\Database\Eloquent\Model;

class MTCostoNoConformidad extends Model
{
    protected $table = "mtcostosnoconformidades"; //nombre de la tabla
    protected $fillable = ['codigo', 'nombre', 'tipo'];

    public function costo_calidad_no_conformidades()
    {
        return $this->hasMany('App\Models\CostoCalidadModels\MTCostoCalidadNoConformidad', 'noconformidad_id');
    }
}
