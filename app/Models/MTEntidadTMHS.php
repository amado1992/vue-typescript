<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEntidadTMHS extends Model
{
    protected $table = "mtentidadestmhs"; //nombre de la tabla
    protected $fillable = ['nombre', 'codigo'];//campos que son llenados en el formulario

    public function expertos()
    {
        return $this->hasMany('App\Models\MTFichaExperto', 'entidad_id');
    }
}
