<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTTipoVAServicios extends Model
{
  public $table = 'mttipovaservicios';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'tipo',
  ];

  protected $casts = [
    'id' => 'integer',
    'tipo' => 'string',
  ];

  public static $rules = [
    'tipo' => 'required',
  ];
}
