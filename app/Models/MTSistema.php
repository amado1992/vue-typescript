<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSistema extends Model
{
    protected $table = "mtsistemas"; //nombre de la tabla
    protected $fillable = ['nombre', 'descripcion'];//campos que son llenados en el formulario

    public function equipos()
    {
        return $this->hasMany('App\Models\MTEquipo', 'sistema_id');
    }
}
