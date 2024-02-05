<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEvaluacion extends Model
{
    protected $table = "mtevaluaciones"; //nombre de la tabla

    public function expertosIdiomasHabilidades()
    {
        return $this->hasMany('App\Models\MTExpertoIdiomaHabilidad','mtevaluacion_id');
    }
}
