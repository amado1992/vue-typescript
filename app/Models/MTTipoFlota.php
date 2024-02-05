<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoFlota extends Model
{
  public $table = 'mttipoflotas';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'tipo_flota',
  ];

  protected $casts = [
    'id' => 'integer',
    'tipo_flota' => 'string',
  ];

  public static $rules = [
    'tipo_flota' => 'required',
  ];
}
