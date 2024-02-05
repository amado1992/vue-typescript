<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTClasificacionAccidente extends Model
{
  public $table = 'mtclasificacionaccidentes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'clasificacion_accidente',
  ];

  protected $casts = [
    'id' => 'integer',
    'clasificacion_accidente' => 'string',
  ];

  public static $rules = [
    'clasificacion_accidente' => 'required',
  ];
}
