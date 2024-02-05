<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTExpedienteElemento extends Model
{
    public $table = 'mtexpedienteelementos';

    public $fillable = [
        'nombreElemento',
        'urlElemento',
        'proceso_id'

    ];

    public function proceso_turismo_mas_higienico_seguro()
    {
        return $this->belongsTo('App\Models\MTProcesoTurismoMasHigienicoSeguro', 'proceso_id');
    }
}
