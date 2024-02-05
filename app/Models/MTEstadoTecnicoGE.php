<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEstadoTecnicoGE extends Model
{
    protected $table = "mtestadostecnicoge"; //nombre de la tabla
    protected $fillable = ['codigo','nombre'];//campos que son llenados en el formulario

}
