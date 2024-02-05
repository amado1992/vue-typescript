<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTGrupoEvaluador extends Model
{
    protected $table = "grupoevaluador"; //nombre de la tabla
    protected $fillable = ['proceso_id', 'mtfichaexperto_id', 'esJefe', 'evalUno', 'evalDos'];//campos que son llenados en el formulario

    public function experto()
    {
        return $this->belongsTo('App\Models\MTFichaExperto', 'mtfichaexperto_id');
    }

    public function proceso()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }

}
