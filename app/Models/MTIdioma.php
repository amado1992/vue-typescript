<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTIdioma extends Model
{
    protected $table = "mtidiomas"; //nombre de la tabla
    protected $fillable = ['nombre', 'codigo'];//campos que son llenados en el formulario

    public function expertos(){

        return $this->belongsToMany(MTFichaExperto::class,'expertoidioma');
    }
}
