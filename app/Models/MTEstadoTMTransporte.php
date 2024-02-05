<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTEstadoTMTransporte extends Model
{
  public $table = 'mtestadotmtransportes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'estado',
  ];

  protected $casts = [
    'id' => 'integer',
    'estado' => 'string',
  ];

  public static $rules = [
    'estado' => 'required',
  ];
}
