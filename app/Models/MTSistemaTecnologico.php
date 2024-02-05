<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSistemaTecnologico extends Model
{
    protected $table = "mtsistemastecnologicos"; //nombre de la tabla
    protected $fillable = [
        'equipo_id',
       // 'reportSistemaTecnolog_id',
        'esContratadoMantenimiento',
        'totalEqInstalado',
        'totalHrTrabajarEqInst',
        'totalHrDejadaTrabjEqInst',
        'coeficienteDispTecnica',
        'mantenimientoReparacion',
        'reparacionCapital',
        'reposicion',
        'observacion',
        'instalacion_id'];//campos que son llenados en el formulario

   /* public function reportSistemaTecnologico()
    {
        return $this->belongsTo('App\Models\MTReportSistemaTecnologico', 'reportSistemaTecnolog_id');
    }*/

    public function equipo()
    {
        return $this->belongsTo('App\Models\MTEquipo', 'equipo_id');
    }

    public function instalacion()
    {
        return $this->belongsTo('App\Models\MTInstalacion', 'instalacion_id');
    }
}
