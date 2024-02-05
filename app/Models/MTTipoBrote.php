<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoBrote extends Model
{
  public $table = 'mttipo_brotes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'tipo_brote'
  ];

  protected $casts = [
    'codigo' => 'string',
    'tipo_brote' => 'string'
  ];

  public static $rules = [
    'tipo_brote' => 'required'
  ];

}
