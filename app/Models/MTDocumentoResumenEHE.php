<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTDocumentoResumenEHE extends Model
{
    public $table = 'mtdocumento_resumen_ehe';

    public $guarded  = [];

    protected $casts = [
        //
    ];

    public static $rules = [
        //
    ];

    public function fichero_resumen()
    {
        return $this->belongsTo(MTEventoHigienicoEpidemiologico::class, 'ehe_id');
    }
}
