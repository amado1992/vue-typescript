<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoMuestra extends Model
{
  public $table = 'mttipo_muestras';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'tipo_muestra'
  ];

  protected $casts = [
    'codigo' => 'string',
    'tipo_muestra' => 'string'
  ];

  public static $rules = [
    'codigo' => 'required',
    'tipo_muestra' => 'required'
  ];

}
