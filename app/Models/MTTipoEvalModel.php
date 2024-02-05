<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoEvalModel extends Model
{
    protected $table = "mttiposevals"; //nombre de la tabla
    protected $fillable = ['nombre', 'codigo'];//campos que son llenados en el formulario

    public function dictamenes(){

        return $this->hasMany(MTDictamenTMHS::class,'mttipoeval_id');
    }
}
