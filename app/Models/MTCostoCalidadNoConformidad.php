<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCostoCalidadNoConformidad extends Model
{
    protected $table = "mtcostocalidadnoconformidades"; //nombre de la tabla
    protected $fillable = ['noconformidad_id', 'reportCostoCalidad_id', 'importe', 'acumulado','tipo'];

    public function costo_no_conformidad()
    {
        return $this->belongsTo('App\Models\MTCostoNoConformidad', 'noconformidad_id');
    }

    public function costo_calidad_report()
    {
        return $this->belongsTo('App\Models\MTCostoCalidadReport', 'reportCostoCalidad_id');
    }
}
