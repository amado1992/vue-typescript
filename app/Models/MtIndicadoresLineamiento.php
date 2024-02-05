<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MtIndicadoresLineamiento extends Model
{
    public $table = 'mtindicadores_lineamiento';

    const CREATED_AT = 'created_at';

    public $fillable = [
        'mes',
        'anno',
        'instalacion_id',
        'licsanitaria_id',
        'avalcitma_id',
        'avalapci_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'mes' => 'integer',
        'anno' => 'integer',
        'instalacion_id' => 'integer',
        'licsanitaria_id' => 'integer',
        'avalcitma_id' => 'integer',
        'avalapci_id' => 'integer',
    ];

  protected $with = [
    'instalaciones:id,nombre,tipoInst_id',
    'licencia:id,estado',
    'avalcitma:id,estado',
    'avalapci:id,estado'
  ];

    public static $rules = [
        'mes' => 'required',
        'anno' => 'required',
        'licsanitaria_id' => 'required',
        'avalcitma_id' => 'required',
        'avalapci_id' => 'required',
    ];

    public function instalaciones()
    {
        return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
    }

    public function licencia()
    {
        return $this->belongsTo(MTLicSanitaria::class,'licsanitaria_id');
    }

    public function avalcitma()
    {
        return $this->belongsTo(MTAvalCitma::class,'avalcitma_id');
    }

    public function avalapci()
    {
        return $this->belongsTo(MTAvalApci::class,'avalapci_id');
    }

}
