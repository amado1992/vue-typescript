<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTAvalCitma extends Model
{
    public $table = 'mtaval_citma_nomenclador';

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

    public static $rules = [

    ];

    public function indicadores()
    {
        return $this->hasMany(MTIndicadoresLineamiento::class, 'avalcitma_id');
    }

}
