<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTExpertoIdiomaHabilidad extends Model
{
    protected $table = "mtexpertoidiomahabilidad"; //nombre de la tabla
    protected $fillable = ['mtexpertoidioma_id', 'mthabilidad_id', 'mtevaluacion_id'];

    public function evaluacion()
    {
        return $this->belongsTo('App\Models\MTEvaluacion', 'mtevaluacion_id');
    }

    public function habilidad()
    {
        return $this->belongsTo('App\Models\MTHabilidad', 'mthabilidad_id');
    }

    public function experto_idioma()
    {
        return $this->belongsTo('App\Models\MTExpertoIdioma', 'mtexpertoidioma_id');
    }
}
