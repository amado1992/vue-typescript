<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTVehiculoTransmision extends Model
{
  public $table = 'mtvehiculo_transmisions';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'vehiculo'
  ];

  protected $casts = [
    'codigo' => 'string',
    'vehiculo' => 'string'
  ];

  public static $rules = [
    'vehiculo' => 'required'
  ];

}
