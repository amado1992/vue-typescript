<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEstadoGE extends Model
{
    protected $table = "mtestadosge"; //nombre de la tabla
    protected $fillable = ['codigo','nombre', 'abreviatura'];//campos que son llenados en el formulario


}
