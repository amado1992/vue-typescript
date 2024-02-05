<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoInstTurismoMasHS extends Model
{
    protected $table = "mttipoinstturismomashs"; //nombre de la tabla
    protected $fillable = [
        'nombre', 'descripcion', 'mttipoinst_id', 'mtvalor_id'];//campos que son llenados en el formulario


    public function tipo_instalacion()
    {
        return $this->belongsTo('App\Models\MTTipoInst', 'mttipoinst_id');
    }

    public function valor()
    {
        return $this->belongsTo(MTValor::class, 'mtvalor_id');
    }
}
