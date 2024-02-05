<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoFoco extends Model
{
  public $table = 'mttipo_focos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'tipo_foco'
  ];

  protected $casts = [
    'codigo' => 'string',
    'tipo_foco' => 'string'
  ];

  public static $rules = [
    'codigo' => 'required',
    'tipo_foco' => 'required'
  ];

}
