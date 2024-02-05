<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAvalApci extends Model
{
    public $table = 'mtaval_apci_nomenclador';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'codigo',
        'estado',
    ];

    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'estado' => 'string',
    ];

    public function indicadores()
    {
        return $this->hasMany(MTIndicadoresLineamiento::class, 'avalapci_id');
    }

}

