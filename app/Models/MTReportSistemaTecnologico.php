<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTReportSistemaTecnologico extends Model
{
    protected $table = "mtreportsistemastecnologicos"; //nombre de la tabla
    protected $fillable = ['codigo', 'mes', 'anno', 'instalacion_id'];//campos que son llenados en el formulario

   /* public function sistemasTecnologicos()
    {
        return $this->hasMany('App\Models\MTSistemaTecnologico', 'reportSistemaTecnolog_id');
    }*/
}
