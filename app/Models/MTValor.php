<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTValor extends Model
{
    protected $table = "mtvalores"; //nombre de la tabla
    protected $fillable = [
        'nombre'];//campos que son llenados en el formulario

    public function tipos_instalaciones()
    {
        return $this->hasMany(MTTipoInstTurismoMasHS::class, 'mtvalor_id');
    }
}
