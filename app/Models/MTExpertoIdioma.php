<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTExpertoIdioma extends Model
{
    protected $table = "expertoidioma"; //nombre de la tabla
    protected $fillable = ['mtidioma_id', 'mtfichaexperto_id'];//campos que son llenados en el formulario

    public function idioma()
    {
        return $this->belongsTo(MTIdioma::class, 'mtidioma_id');
    }

    public function experto()
    {
        return $this->belongsTo(MTFichaExperto::class, 'mtfichaexperto_id');
    }
}
