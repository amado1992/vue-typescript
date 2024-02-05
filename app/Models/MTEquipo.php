<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEquipo extends Model
{
    protected $table = "mtequipos"; //nombre de la tabla
    protected $fillable = ['nombre', 'descripcion', 'sistema_id'];//campos que son llenados en el formulario

    public function sistema()
    {
        return $this->belongsTo('App\Models\MTSistema', 'sistema_id');
    }

    public function sistemasTecnologicos()
    {
        return $this->hasMany('App\Models\MTSistemaTecnologico', 'equipo_id');
    }
}
