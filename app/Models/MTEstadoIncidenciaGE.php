<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEstadoIncidenciaGE extends Model
{
    protected $table = "mtestadoinciadenciasge"; //nombre de la tabla
    protected $fillable = ['codigo','nombre'];//campos que son llenados en el formulario

}
