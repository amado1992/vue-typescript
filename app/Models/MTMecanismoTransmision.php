<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTMecanismoTransmision extends Model
{
  public $table = 'mtmecanismo_transmisions';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'codigo', 'mecanismo_transmision'
  ];

  protected $casts = [
    'codigo' => 'string',
    'mecanismo_transmision' => 'string'
  ];

  public static $rules = [
    'mecanismo_transmision' => 'required'
  ];

}
