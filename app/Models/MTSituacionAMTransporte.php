<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTSituacionAMTransporte extends Model
{
  public $table = 'mtsituacionamtransportes';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'situacion_actual',
  ];

  protected $casts = [
    'id' => 'integer',
    'situacion_actual' => 'string',
  ];

  public static $rules = [
    'situacion_actual' => 'required',
  ];
}
