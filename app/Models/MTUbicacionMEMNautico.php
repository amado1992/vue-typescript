<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTUbicacionMEMNautico extends Model
{
  public $table = 'mtubicacionmemnauticos';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'ubicacion',
  ];

  protected $casts = [
    'id' => 'integer',
    'ubicacion' => 'string',
  ];

  public static $rules = [
    'ubicacion' => 'required',
  ];
}
