<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoCaso extends Model
{
  public $table = 'mttipo_casos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'tipo_caso'
  ];

  protected $casts = [
    'codigo' => 'string',
    'tipo_caso' => 'string'
  ];

  public static $rules = [
    'tipo_caso' => 'required'
  ];

}
