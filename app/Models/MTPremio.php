<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTPremio extends Model
{
  public $table = 'mtpremio';

  const CREATED_AT = 'created_at';

    public $fillable = [
        'nombre',
        'fecha',
        'categoriapremio_id',
        'instalacion_id',
    ];

  protected $casts = [
    'id' => 'integer',
    'nombre' => 'string|alpha',
    'fecha' => 'date',
    'categoriapremio_id' => 'integer',
    'instalacion_id' => 'integer'
  ];

  public static $rules = [
    'nombre' => 'required',
    'fecha' => 'required',
    'categoriapremio_id' => 'required',
  ];

  public function categoriapremios()
  {
    return $this->belongsTo(MTCategoriaPremio::class, 'categoriapremio_id');
  }

  public function instalaciones()
  {
    return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
  }

}
