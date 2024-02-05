<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTOnit extends Model
{
  public $table = 'mtonits';

  const CREATED_AT = 'created_at';

  public $fillable = [
    'activo',
    'codigo',
    'nombre'
  ];

  protected $casts = [
    'id' => 'integer',
    'activo' => 'string',
    'codigo' => 'string',
    'nombre' => 'string'
  ];

  public static $rules = [
    'activo' => 'required',
    'codigo' => 'required',
    'nombre' => 'required'
  ];

}
