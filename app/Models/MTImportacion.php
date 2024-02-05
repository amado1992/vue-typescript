<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTImportacion extends Model
{
    public $table = 'mtimportacions';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'mes',
        'anno',
        'plan',
        'real',
        'empresa_id',
        'indicador_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'mes' => 'integer',
        'anno' => 'integer',
        'plan' => 'integer',
        'real' => 'integer',
        'empresa_id' => 'integer',
        'indicador_id' => 'integer',
    ];
    protected $with = [
        'indicador'
    ];

    public static $rules = [
        'mes' => 'required',
        'anno' => 'required',
        'plan' => 'required',
        'real' => 'required',
        'empresa_id' => 'required',
        'indicador_id' => 'required',
    ];


    public function empresa()
    {
        return $this->belongsTo(MTEmpresa::class);
    }

    public function indicador()
    {
        return $this->belongsTo(MTIndicador::class);
    }

}
