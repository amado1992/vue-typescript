<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTVehiculoAjeno extends Model
{
  public $table = 'mtvehiculoajeno';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'tipo',
    'marca',
    'matricula',
  ];

  protected $casts = [
    'id' => 'integer',
    'tipo' => 'string',
    'marca' => 'string',
    'matricula' => 'string',
  ];

  public static $rules = [
    'tipo' => 'required',
    'marca' => 'required',
    'matricula' => 'required',
  ];
}
