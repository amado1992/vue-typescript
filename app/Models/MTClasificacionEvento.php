<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTClasificacionEvento extends Model
{
  public $table = 'mtclasificacion_eventos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'clasificacion_evento'
  ];

  protected $casts = [
    'codigo' => 'string',
    'clasificacion_evento' => 'string'
  ];

  public static $rules = [
    'codigo' => 'required',
    'clasificacion_evento' => 'required'
  ];

}
