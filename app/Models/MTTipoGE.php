<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoGE extends Model
{
    protected $table = "mttiposge"; //nombre de la tabla
    protected $fillable = ['codigo','nombre'];//campos que son llenados en el formulario

}
