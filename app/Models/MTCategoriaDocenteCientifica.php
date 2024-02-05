<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCategoriaDocenteCientifica extends Model
{
    protected $table = "mtcategoriasdocentecientifica"; //nombre de la tabla
    protected $fillable = ['nombre', 'descripcion'];//campos que son llenados en el formulario

    public function expertos()
    {
        return $this->hasMany('App\Models\MTFichaExperto', 'mtcategdocentecientifica_id');
    }

}
